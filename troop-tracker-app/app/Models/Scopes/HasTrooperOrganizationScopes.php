<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\TrooperOrganization;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Trooper model.
 */
trait HasTrooperOrganizationScopes
{
    protected function scopeActiveMembers(Builder $query): Builder
    {
        return $query
            ->where(TrooperOrganization::MEMBERSHIP_STATUS, MembershipStatus::Active);
    }


}