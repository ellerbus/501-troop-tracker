<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\MembershipStatus;
use App\Enums\OrganizationType;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperAssignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Trait containing local scopes for the Organization model.
 */
trait HasOrganizationScopes
{
    /**
     * Scope a query to eager load the full organization hierarchy, starting from top-level organizations.
     * This loads up to three levels deep (organization -> region -> unit).
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @return Builder<Organization>
     */
    protected function scopeFullyLoaded(Builder $query): Builder
    {
        return $query->with('organizations.organizations.organizations')
            ->where('type', OrganizationType::Organization)
            ->orderBy(self::NAME);
    }

    /**
     * Scope a query to only include organizations of the 'organization' type.
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @return Builder<Organization>
     */
    protected function scopeOfTypeOrganizations(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Organization);
    }

    /**
     * Scope a query to only include organizations of the 'region' type.
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @return Builder<Organization>
     */
    protected function scopeOfTypeRegions(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Region);
    }

    /**
     * Scope a query to only include organizations of the 'unit' type.
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @return Builder<Organization>
     */
    protected function scopeOfTypeUnits(Builder $query): Builder
    {
        return $query->where(self::TYPE, OrganizationType::Unit);
    }

    /**
     * Scope a query to only include top-level organizations that have active trooper assignments.
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @param int|null $trooper_id If provided, filters to organizations where this specific trooper is active.
     * @return Builder<Organization>
     */
    protected function scopeWithActiveTroopers(Builder $query, ?int $trooper_id = null): Builder
    {
        return $query
            ->orderBy(self::NAME)
            ->where(self::TYPE, OrganizationType::Organization)
            ->whereHas('trooper_assignments', function ($q) use ($trooper_id)
            {
                $q->where('member', true);

                if ($trooper_id)
                {
                    $q->where('trooper_id', $trooper_id);
                }
            });
    }

    /**
     * Scope a query to eager load all assignments for a specific trooper.
     *
     * @param Builder<Organization> $query The Eloquent query builder.
     * @param int $trooper_id The ID of the trooper whose assignments should be loaded.
     * @return Builder<Organization>
     */
    protected function scopeWithAllAssignments(Builder $query, int $trooper_id): Builder
    {
        return $query->orderBy(self::SEQUENCE)->with([
            'parent',
            'trooper_assignments' => function ($q) use ($trooper_id)
            {
                $q->where(TrooperAssignment::TROOPER_ID, $trooper_id);
            }
        ]);
    }

    /**
     * Scope: limit to troopers that can be approved by a given moderator.
     *
     * @param Builder $query
     * @param Organization $moderator
     * @return Builder
     */
    protected function scopeModeratedBy(Builder $query, Trooper $moderator): Builder
    {
        return $query->whereExists(function ($sub) use ($moderator)
        {
            $sub->select(DB::raw(1))
                ->from('tt_trooper_assignments as ta_moderator')
                ->join('tt_organizations as org_moderator', 'ta_moderator.organization_id', '=', 'org_moderator.id')
                ->join('tt_trooper_assignments as ta_candidate', 'ta_candidate.organization_id', '=', 'tt_organizations.id')
                ->join('tt_organizations as org_candidate', 'ta_candidate.organization_id', '=', 'org_candidate.id')
                ->where('ta_moderator.trooper_id', $moderator->id)
                ->where('ta_moderator.moderator', true)
                ->whereRaw('org_candidate.node_path LIKE CONCAT(org_moderator.node_path, "%")');
        });
    }
}