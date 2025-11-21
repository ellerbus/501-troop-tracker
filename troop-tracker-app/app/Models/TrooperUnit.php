<?php

namespace App\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\TrooperUnit as BaseTrooperUnit;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasTrooperUnitScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperUnit extends BaseTrooperUnit
{
    use HasTrooperUnitScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::TROOPER_ID,
        self::UNIT_ID,
        self::NOTIFY,
        self::MEMBERSHIP_STATUS,
        self::MEMBERSHIP_ROLE,
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
            self::MEMBERSHIP_STATUS => MembershipStatus::class,
            self::MEMBERSHIP_ROLE => MembershipRole::class,
        ]);
    }
}
