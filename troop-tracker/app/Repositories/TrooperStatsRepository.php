<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Models\EventSignUp;
use App\Models\Squad;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Repository for handling business logic related to event sign-ups.
 */
class TrooperStatsRepository
{
    /**
     * @param ClubRepository $clubs The club repository.
     */
    public function __construct(private readonly ClubRepository $clubs)
    {
    }

    /**
     * Finds an event sign-up by its primary key.
     *
     * @param int $id The ID of the event sign-up.
     * @return EventSignUp|null The EventSignUp model if found, otherwise null.
     */
    public function getById(int $id): ?EventSignUp
    {
        return EventSignUp::find($id);
    }

    /**
     * Calculates a trooper's rank based on the total count of completed troops.
     * Note: This can be a slow query and is a candidate for optimization,
     * potentially by caching the results in a daily scheduled job.
     *
     * @param int $trooperId The ID of the trooper to rank.
     * @return int The trooper's rank (1-based). Returns 0 if the trooper has no completed troops.
     */
    public function getTrooperRanking(int $trooperId): int
    {
        //  TODO push to daily job to store this

        // Get all trooper IDs ranked by completed troop count
        $ranked = EventSignUp::query()
            ->where('status', 3)
            ->whereHas('event', fn($q) => $q->where('closed', true))
            ->selectRaw('trooperid, COUNT(1) as total')
            ->groupBy('trooperid')
            ->orderByDesc('total')
            ->pluck('trooperid');

        // Find the index of the trooper and return rank (1-based)
        $rank = $ranked->search($trooperId);

        if ($rank !== false)
        {
            return $rank + 1;
        }

        return 0;
    }

    /**
     * Automatically confirms old, unconfirmed sign-ups for closed events.
     *
     * This method transitions sign-ups with a status of 'unconfirmed' (0) to 'attended' (3)
     * if the sign-up is older than 6 months and the associated event is closed. This is intended
     * to be run as a scheduled task to clean up the database.
     */
    public function autoConfirm(): void
    {
        DB::table('event_sign_up')
            ->join('events', 'events.id', '=', 'event_sign_up.troopid')
            ->where('event_sign_up.status', 0)
            ->where('event_sign_up.signuptime', '<=', Carbon::now()->subMonths(6))
            ->where('events.closed', 1)
            ->update(['event_sign_up.status' => 3]);
    }

    /**
     * Get the count of finished troops for a trooper since a given date.
     *
     * @param int $trooper_id The ID of the trooper.
     * @param DateTimeInterface|null $since The date to count troops from. If null, counts all troops.
     * @return int The total count of attended events.
     */
    public function getTroopCountSince(int $trooper_id, ?DateTimeInterface $since): int
    {
        return EventSignUp::query()
            ->where('status', 3)
            ->where('trooperid', $trooper_id)
            ->whereHas('event', function ($query) use ($since)
            {
                $query->where('closed', true);

                if ($since)
                {
                    $query->where('dateStart', '>', $since);
                }
            })
            ->distinct('event_sign_up.id')
            ->count('event_sign_up.id');
    }

    /**
     * Get troop counts grouped by club for a given trooper.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return Collection A collection of objects, each containing 'id', 'name', and 'troop_count'
     *                    for each club the trooper has trooped with.
     */
    public function getTroopCountsByClub(int $trooper_id): Collection
    {
        // Step 1: Get signup counts per club
        $sign_ups = DB::table('event_sign_up')
            ->join('costumes', 'event_sign_up.costume', '=', 'costumes.id')
            ->where('event_sign_up.trooperid', $trooper_id)
            ->select('costumes.club_id', DB::raw('count(*) as troop_count'))
            ->groupBy('costumes.club_id')
            ->pluck('troop_count', 'costumes.club_id'); // returns [club_id => count]

        // Step 2: Load all clubs as Eloquent models
        $clubs = $this->clubs->findAllActive();

        // Step 3: Attach troop_count to each Club model
        $clubs->each(function ($club) use ($sign_ups)
        {
            $club->troop_count = $sign_ups[$club->id] ?? 0;
        });

        return $clubs;
    }

    /**
     * Finds the most frequently used costume for a trooper, excluding specific non-costume IDs.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return int|null The ID of the favorite costume, or null if none are found.
     */
    public function getFavoriteCostumeForTrooper(int $trooper_id): ?int
    {
        return EventSignUp::where('trooperid', $trooper_id)
            ->whereNotIn('costume', [706, 720, 721])
            ->select('costume', DB::raw('COUNT(1) as count'))
            ->groupBy('costume')
            ->orderByDesc('count')
            ->limit(1)
            ->pluck('costume')
            ->first();
    }

    /**
     * Calculates the total direct and indirect charity funds raised by a trooper across all their events.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return array{direct: float, indirect: float} An associative array with total direct and indirect funds.
     */
    public function getTotalCharityFundsForTrooper(int $trooper_id): array
    {
        $direct = Event::join('event_sign_up', 'events.id', '=', 'event_sign_up.troopid')
            ->where('event_sign_up.trooperid', $trooper_id)
            ->sum('events.charityDirectFunds');

        $indirect = Event::join('event_sign_up', 'events.id', '=', 'event_sign_up.troopid')
            ->where('event_sign_up.trooperid', $trooper_id)
            ->sum('events.charityIndirectFunds');

        return [
            'direct' => $direct,
            'indirect' => $indirect,
        ];
    }

    /**
     * Calculates the total volunteer hours for a trooper across all their events.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return float The total number of volunteer hours.
     */
    public function getTotalCharityHoursForTrooper(int $trooper_id): float
    {
        $hours = Event::join('event_sign_up', 'events.id', '=', 'event_sign_up.troopid')
            ->where('event_sign_up.trooperid', $trooper_id)
            ->select(DB::raw('SUM(TIMESTAMPDIFF(HOUR, events.dateStart, events.dateEnd) + events.charityAddHours) as total_hours'))
            ->value('total_hours') ?? 0;

        if (is_string($hours))
        {
            return (int) $hours;
        }

        return $hours;
    }

    public function getAwardsForTrooper(int $trooper_id): array
    {
        $awards = [];

        // Check if supporter
        if ($this->isSupporter($trooper_id))
        {
            $awards[] = 'Supporter Award';
        }

        $squads = Squad::all();
        $trooped_squads = Event::join('event_sign_up', 'events.id', '=', 'event_sign_up.troopid')
            ->where('event_sign_up.trooperid', $trooper_id)
            ->where('event_sign_up.status', 3)
            ->where('events.closed', 1)
            ->distinct()
            ->pluck('events.squad');

        // Squad completion award
        if (count($squads) === count($trooped_squads))
        {
            $awards[] = 'Trooped Every Squad!';
        }

        // Get count of completed troops
        $count = EventSignUp::query()
            ->where('status', 3)
            ->where('trooperid', $trooper_id)
            ->count();

        // Troop count awards
        $milestones = [1 => 'First Troop Completed!',
            10 => '10 Troops',
            25 => '25 Troops',
            50 => '50 Troops',
            75 => '75 Troops',
            100 => '100 Troops',
            150 => '150 Troops',
            200 => '200 Troops',
            250 => '250 Troops',
            300 => '300 Troops',
            400 => '400 Troops',
            500 => '500 Troops',
            501 => '501 Troops Award'
        ];

        foreach ($milestones as $threshold => $label)
        {
            if ($count >= $threshold)
            {
                $awards[] = $label;
            }
        }

        return $awards;
    }

    protected function isSupporter(int $trooper_id): bool
    {
        // Replace with actual logic or service call
        return DB::table('supporters')->where('trooperid', $trooper_id)->exists();
    }
}