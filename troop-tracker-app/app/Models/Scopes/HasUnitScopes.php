<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Unit model.
 */
trait HasUnitScopes
{
    protected function scopeActive(Builder $query): Builder
    {
        $query->where(self::ACTIVE, true);

        return $query->orderBy(self::NAME);
    }
}