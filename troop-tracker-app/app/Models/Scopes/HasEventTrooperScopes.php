<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasEventTrooperScopes
{
    public function scopeCostumeCountByTrooper(Builder $query, int $trooper_id): Collection
    {
        return $query->with(['club_costume.club'])
            ->get()
            ->groupBy(fn($et) => "{$et->trooper_id}-{$et->costume_id}-{$et->club_costume->club_id}")
            ->map(fn($group) => new TrooperCostumeClubSummary(
                $group->first()->trooper_id,
                $group->first()->costume_id,
                $group->first()->club_costume->name,
                $group->first()->club_costume->club->id,
                $group->first()->club_costume->club->name,
                $group->count()
            ))->values();
    }

    public function scopeClubCountByTrooper(Builder $query, int $trooper_id): Collection
    {
        // Get all appearances for this trooper, with club relationship loaded
        $appearances = $query->with(['club_costume.club'])
            ->where(self::TROOPER_ID, $trooper_id)
            ->whereHas('club_costume.club') // ensures both costume and club exist
            ->get();

        // Group by club_id and count
        return $appearances
            ->groupBy(fn($et) => $et->club_costume->club_id)
            ->map(fn($group, $club_id) => [
                'club_id' => (int) $club_id,
                'troop_count' => $group->count(),
            ])
            ->pluck('troop_count', 'club_id');
    }

    public function scopeUpcomingByTrooper(Builder $query, int $trooper_id): Builder
    {
        return self::troopDetails($query, $trooper_id, false);
    }

    public function scopeHistoricalByTrooper(Builder $query, int $trooper_id): Builder
    {
        return self::troopDetails($query, $trooper_id, true);
    }

    private function troopDetails(Builder $query, int $trooper_id, bool $closed): Builder
    {
        return $query
            ->where(self::TROOPER_ID, $trooper_id)
            ->where(self::STATUS, 0)
            ->whereHas('event', fn($q) => $q->where('closed', $closed))
            ->with([
                'event.squad',
                'trooper',
                'club_costume.club',
            ]);
    }
}