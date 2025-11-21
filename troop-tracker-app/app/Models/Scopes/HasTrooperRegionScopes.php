<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperRegion;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Trooper model.
 */
trait HasTrooperRegionScopes
{
    protected function scopeActiveMembers(Builder $query): Builder
    {
        return $query
            ->where(TrooperRegion::MEMBERSHIP_STATUS, MembershipStatus::Active);
    }


}