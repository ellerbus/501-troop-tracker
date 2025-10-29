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
}