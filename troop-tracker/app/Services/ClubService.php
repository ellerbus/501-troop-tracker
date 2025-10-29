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
     * Retrieves all active clubs, ordered by name.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Club>|Club[]
     */
    public function findAllActive(bool $include_squads = false): \Illuminate\Database\Eloquent\Collection
    {
        $query = Club::where('active', true)->orderBy('name');

        if ($include_squads)
        {
            $query->with('squads');
        }

        return $query->get();

    }
}