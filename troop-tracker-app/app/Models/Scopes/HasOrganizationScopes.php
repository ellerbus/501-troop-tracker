<?php

declare(strict_types=1);

namespace App\Models\Scopes;

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
    protected function scopeActive(Builder $query): Builder
    {
        $query->where(self::ACTIVE, true);

        return $query->orderBy(self::NAME);
    }
}