<?php

namespace App\Policies;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use App\Models\TrooperRegion;
use App\Models\TrooperUnit;

/**
 * Defines authorization rules for trooper-related actions.
 */
class TrooperPolicy
{
    use HasTrooperPermissionsTrait;

    /**
     * Determine whether the user can view a list of troopers.
     */
    public function viewAny(Trooper $trooper): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can view a specific trooper's profile.
     */
    public function view(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can create troopers. Always false as creation is handled by registration.
     */
    public function create(Trooper $trooper): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update a trooper's profile.
     */
    public function update(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can soft-delete a trooper.
     */
    public function delete(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can restore a soft-deleted trooper.
     */
    public function restore(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can permanently delete a trooper.
     */
    public function forceDelete(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can approve a pending trooper registration.
     * Admins can approve any trooper. Moderators can only approve troopers
     * within their moderated organizations, regions, or units.
     */
    public function approve(Trooper $trooper, Trooper $subject): bool
    {
        if ($this->isAdmin($trooper))
        {
            return true;
        }

        return $this->sharesModeratedScope($trooper, $subject);
    }

    /**
     * Check if a moderator shares a common scope (organization, region, or unit) with a candidate trooper.
     *
     * @param Trooper $approver The moderator attempting to approve.
     * @param Trooper $candidate The trooper pending approval.
     * @return bool True if they share a moderated scope, false otherwise.
     */
    protected function sharesModeratedScope(Trooper $approver, Trooper $candidate): bool
    {
        $moderated_orgs = $approver->organizations()
            ->wherePivot(TrooperOrganization::MEMBERSHIP_ROLE, MembershipRole::Moderator)
            ->wherePivot(TrooperOrganization::MEMBERSHIP_STATUS, MembershipStatus::Active)
            ->pluck('tt_organizations.id');

        $moderated_regions = $approver->regions()
            ->wherePivot(TrooperRegion::MEMBERSHIP_ROLE, MembershipRole::Moderator)
            ->wherePivot(TrooperRegion::MEMBERSHIP_STATUS, MembershipStatus::Active)
            ->pluck('tt_regions.id');

        $moderated_units = $approver->units()
            ->wherePivot(TrooperUnit::MEMBERSHIP_ROLE, MembershipRole::Moderator)
            ->wherePivot(TrooperUnit::MEMBERSHIP_STATUS, MembershipStatus::Active)
            ->pluck('tt_units.id');

        $candidate_orgs = $candidate->organizations()->pluck('tt_organizations.id');
        $candidate_regions = $candidate->regions()->pluck('tt_regions.id');
        $candidate_units = $candidate->units()->pluck('tt_units.id');

        return
            $candidate_orgs->intersect($moderated_orgs)->isNotEmpty() ||
            $candidate_regions->intersect($moderated_regions)->isNotEmpty() ||
            $candidate_units->intersect($moderated_units)->isNotEmpty();
    }
}
