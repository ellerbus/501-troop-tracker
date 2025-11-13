<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EventSignUp;
use Illuminate\Support\Collection;

/**
 * Repository for handling data access operations related to Event Sign-Ups.
 */
class EventSignUpRepository
{
    /**
     * Retrieves a collection of upcoming troops (unconfirmed sign-ups for open events) for a specific trooper.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return Collection A collection of upcoming troops, each with event_name, start_date, club_name, and costume_name.
     */
    public function getUpcomingTroops(int $trooper_id): Collection
    {
        return EventSignUp::query()
            ->join('events', 'event_sign_up.troopid', '=', 'events.id')
            ->join('troopers', 'event_sign_up.trooperid', '=', 'troopers.id')
            ->join('costumes', 'event_sign_up.costume', '=', 'costumes.id')
            ->join('clubs', 'costumes.club_id', '=', 'clubs.id')
            ->join('squads', 'events.squad', '=', 'squads.id')
            ->where('troopers.id', $trooper_id)
            ->where('events.closed', false)
            ->where('event_sign_up.status', 0)
            ->orderBy('events.dateEnd', 'asc')
            ->select([
                'squads.image_path_sm',
                'events.name as event_name',
                'events.dateStart as start_date',
                'clubs.name as club_name',
                'costumes.costume as costume_name'
            ])
            ->get();
    }

    /**
     * Retrieves a collection of historical troops (attended sign-ups for closed events) for a specific trooper.
     *
     * @param int $trooper_id The ID of the trooper.
     * @return Collection A collection of historical troops, each with event_name, start_date, club_name, and costume_name.
     */
    public function getHistoricalTroops(int $trooper_id): Collection
    {
        return EventSignUp::query()
            ->join('events', 'event_sign_up.troopid', '=', 'events.id')
            ->join('troopers', 'event_sign_up.trooperid', '=', 'troopers.id')
            ->join('costumes', 'event_sign_up.costume', '=', 'costumes.id')
            ->join('clubs', 'costumes.club_id', '=', 'clubs.id')
            ->join('squads', 'events.squad', '=', 'squads.id')
            ->where('troopers.id', $trooper_id)
            ->where('events.closed', true)
            ->where('event_sign_up.status', 3)
            ->orderBy('events.dateEnd', 'asc')
            ->select([
                'squads.image_path_sm',
                'events.name as event_name',
                'events.dateStart as start_date',
                'clubs.name as club_name',
                'costumes.costume as costume_name'
            ])
            ->get();
    }
}