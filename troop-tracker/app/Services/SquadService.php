<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Squad;

/**
 * Service for handling business logic related to clubs.
 */
class SquadService
{
    /**
     * Retrieves all active clubs, ordered by name.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Squad>|Squad[]
     */
    public function findAllActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Squad::where('active', true)->orderBy('name')->get();
    }

    /**
     * Checks of the given squad exists within the club
     * 
     * @param int $squad_id
     * @param int $club_id
     * @return bool
     */
    public function isActive(int $squad_id, int $club_id): bool
    {
        return Squad::where('id', $squad_id)
            ->where('club_id', $club_id)
            ->exists();
    }
}