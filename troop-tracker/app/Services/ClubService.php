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
    public function findAllActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Club::where('active', true)->orderBy('name')->get();
    }
}