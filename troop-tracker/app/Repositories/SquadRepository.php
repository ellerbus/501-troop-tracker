<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Squad;
use Illuminate\Support\Collection;

/**
 * Repository for handling business logic related to clubs.
 */
class SquadRepository
{
    /**
     * Retrieves all active clubs, ordered by name.
     *
     * @return Collection<int, Squad>|Squad[]
     */
    public function findAllActive(): Collection
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

    /**
     * Checks of the given squad exists within the club
     * 
     * @param int $squad_id
     * @param int $club_id
     * @return bool
     */
    public function isNotActive(int $squad_id, int $club_id): bool
    {
        return !$this->isActive($squad_id, $club_id);
    }
}