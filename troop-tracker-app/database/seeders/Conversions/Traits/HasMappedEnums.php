<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions\Traits;

use App\Enums\MembershipStatus;

trait HasMappedEnums
{
    protected function mapLegacyStatus(int $value): string
    {
        return match ((int) $value)
        {
            0 => MembershipStatus::None->value,
            1 => MembershipStatus::Member->value,
            2 => MembershipStatus::Reserve->value,
            3 => MembershipStatus::Retired->value,
            4 => MembershipStatus::Handler->value,
            default => MembershipStatus::None->value, // fallback
        };
    }

}