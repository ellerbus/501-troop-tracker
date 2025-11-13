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
        return Squad::where(Squad::ACTIVE, true)->orderBy(Squad::NAME)->get();
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
        return Squad::where(Squad::ID, $squad_id)
            ->where(Squad::CLUB_ID, $club_id)
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