<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\OrganizationType;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Organization model.
 */
trait HasOrganizationScopes
{
    /**
     * Scope a query to only include organizations of type 'region'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegions(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Region);
    }
    /**
     * Scope a query to only include organizations of type 'unit'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnits(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Unit);
    }
}