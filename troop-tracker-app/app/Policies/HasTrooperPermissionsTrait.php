<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\MembershipRole;
use App\Models\Trooper;

trait HasTrooperPermissionsTrait
{
    protected function isAdmin(Trooper $trooper): bool
    {
        return $trooper->membership_role == MembershipRole::Administrator;
    }

    // protected function isModeratorOf(Trooper $trooper, ?): bool
    // {
    //     return $this->isAdmin($trooper) || $trooper->membership_role == MembershipRole::Moderator;
    // }
}