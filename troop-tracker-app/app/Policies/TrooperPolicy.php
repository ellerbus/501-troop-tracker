<?php

namespace App\Policies;

use App\Models\Trooper;
use App\Models\TrooperOrganization;
use App\Models\TrooperRegion;
use App\Models\TrooperUnit;

class TrooperPolicy
{
    use HasTrooperPermissionsTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Trooper $trooper): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Trooper $trooper): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Trooper $trooper, Trooper $subject): bool
    {
        return $this->isModerator($trooper);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function approve(Trooper $trooper, Trooper $subject): bool
    {
        if ($this->isAdmin($trooper))
        {
            return true;
        }


    }

    protected function sharesModeratedScope(Trooper $approver, Trooper $candidate): bool
    {
        $moderated_orgs = $approver->organizations()->wherePivot(TrooperOrganization::MEMBERSHIP_STATUS, 'moderator')->pluck('id');
        $moderated_regions = $approver->regions()->wherePivot(TrooperRegion::MEMBERSHIP_STATUS, 'moderator')->pluck('id');
        $moderated_units = $approver->units()->wherePivot(TrooperUnit::MEMBERSHIP_STATUS, 'moderator')->pluck('id');

        $candidate_orgs = $candidate->organizations()->pluck('id');
        $candidate_regions = $candidate->regions()->pluck('id');
        $candidate_units = $candidate->units()->pluck('id');

        return
            $candidate_orgs->intersect($moderated_orgs)->isNotEmpty() ||
            $candidate_regions->intersect($moderated_regions)->isNotEmpty() ||
            $candidate_units->intersect($moderated_units)->isNotEmpty();
    }
}
