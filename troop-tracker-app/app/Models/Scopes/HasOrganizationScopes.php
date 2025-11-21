<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\Region;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Organization model.
 */
trait HasOrganizationScopes
{
    /**
     * Scope a query to only include active organizations, with optional eager loading of related regions and costumes.
     *
     * @param Builder<self> $query The Eloquent query builder.
     * @return Builder<self>
     */
    protected function scopeActive(Builder $query, bool $eager_load_all = false): Builder
    {
        $query->where(self::ACTIVE, true);

        if ($eager_load_all)
        {
            $query->with([
                'regions' => fn($q) => $q->active()->orderBy(Region::NAME),
                'regions.units' => fn($q) => $q->active()->orderBy(Unit::NAME),
            ]);
        }

        return $query->orderBy(self::NAME);
    }
}