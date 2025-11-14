<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasSquadScopes
{
    /**
     * Scope a query to only include active squads.
     *
     * @param Builder $query
     * @param int $club_id Optional club ID to filter squads by.
     * @param int $squad_id Optional squad ID to filter by.
     * @return Builder
     */
    protected function scopeActive(Builder $query, int $club_id = null, int $squad_id = null): Builder
    {
        $query->where(self::ACTIVE, true);

        if ($club_id)
        {
            $query->where(self::CLUB_ID, $club_id);
        }

        if ($squad_id)
        {
            $query->where(self::ID, $squad_id);
        }

        return $query->orderBy(self::NAME);
    }
}