<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Club;

/**
 * Service for handling business logic related to clubs.
 */
class ClubService
{
    /**
     * Summary of getById
     * 
     * @param int $id
     * @return Club
     */
    public function getById(int $id): Club
    {
        return Club::findOrFail($id);
    }

    /**
     * Retrieves all active clubs, ordered by name - and cached.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Club>|Club[]
     */
    public function findAllActive(bool $include_squads = false): \Illuminate\Database\Eloquent\Collection
    {
        return once(fn() => $this->queryActiveClubs($include_squads));

    }

    private function queryActiveClubs(bool $include_squads): \Illuminate\Database\Eloquent\Collection
    {
        $query = Club::where('active', true)->orderBy('name');

        if ($include_squads)
        {
            $query->with('squads');
        }

        return $query->get();
    }
}