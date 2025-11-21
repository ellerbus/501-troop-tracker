<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait containing local scopes for the Trooper model.
 */
trait HasTrooperScopes
{
    /**
     * Scope a query to find a trooper by their username.
     *
     * @param Builder<self> $query The Eloquent query builder.
     * @param string $username The username to search for.
     * @return Builder<self>
     */
    protected function scopeByUsername(Builder $query, string $username): Builder
    {
        return $query->where(self::USERNAME, $username);
    }

    /**
     * Scope a query to find all troopers not approved.
     *
     * @param Builder<self> $query The Eloquent query builder.
     * @return Builder<self>
     */
    protected function scopePendingApprovals(Builder $query): Builder
    {
        return $query->where(self::MEMBERSHIP_STATUS, MembershipStatus::Pending)
            ->orderBy(self::NAME);
    }
}