<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\MembershipStatus;
use App\Enums\OrganizationType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Organization model.
 */
trait HasOrganizationScopes
{
    /**
     * Scope a query to eager load the full organization hierarchy, starting from top-level organizations.
     * This loads up to three levels deep (organization -> region -> unit).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullyLoaded(Builder $query): Builder
    {
        return $query->with('organizations.organizations.organizations')
            ->where('type', OrganizationType::Organization)
            ->orderBy(Organization::NAME);
    }

    /**
     * Scope a query to only include organizations of the 'organization' type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfTypeOrganizations(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Organization);
    }

    /**
     * Scope a query to only include organizations of the 'region' type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfTypeRegions(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Region);
    }

    /**
     * Scope a query to only include organizations of the 'unit' type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfTypeUnits(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Unit);
    }

    /**
     * Scope a query to only include top-level organizations that have active trooper assignments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The Eloquent query builder.
     * @param int|null $trooper_id If provided, filters to organizations where this specific trooper is active.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActiveTroopers(Builder $query, ?int $trooper_id = null): Builder
    {
        return $query
            ->orderBy(self::NAME)
            ->where(self::TYPE, OrganizationType::Organization)
            ->whereHas('trooper_assignments', function ($q) use ($trooper_id)
            {
                $q->where('membership_status', MembershipStatus::Active);

                if ($trooper_id)
                {
                    $q->where('trooper_id', $trooper_id);
                }
            });
    }
}