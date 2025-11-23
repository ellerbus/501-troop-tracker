<?php

namespace App\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\TrooperAssignment as BaseTrooperAssignment;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasTrooperRegionScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperAssignment extends BaseTrooperAssignment
{
    use HasTrooperRegionScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected function casts(): array
    {
        return array_merge($this->casts, [
            self::MEMBERSHIP_STATUS => MembershipStatus::class,
            self::MEMBERSHIP_ROLE => MembershipRole::class,
        ]);
    }
}
