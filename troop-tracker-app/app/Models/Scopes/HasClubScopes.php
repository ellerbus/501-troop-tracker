<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasClubScopes
{
    /**
     * Scope a query to only include active clubs.
     *
     * @param Builder $query
     * @param bool $include_squads Eager load squads for the clubs.
     * @param bool $include_costumes Eager load costumes for the clubs.
     * @return Builder
     */
    protected function scopeActive(Builder $query, bool $include_squads = false, bool $include_costumes = false): Builder
    {
        $query->where(self::ACTIVE, true);

        $with = [];

        if ($include_squads)
        {
            $with[] = 'squads';
        }

        if ($include_costumes)
        {
            $with[] = 'club_costumes';
        }

        if (!empty($with))
        {
            $query->with($with);
        }

        return $query->orderBy(self::NAME);
    }
}