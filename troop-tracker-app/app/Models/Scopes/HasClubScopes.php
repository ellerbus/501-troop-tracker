<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Club model.
 */
trait HasClubScopes
{
    /**
     * Scope a query to only include active clubs, with optional eager loading of related squads and costumes.
     *
     * @param Builder<self> $query The Eloquent query builder.
     * @param bool $include_squads Whether to eager load the 'squads' relationship.
     * @param bool $include_costumes Whether to eager load the 'club_costumes' relationship.
     * @return Builder<self>
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