<?php

namespace App\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\TrooperRegion as BaseTrooperRegion;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperRegion extends BaseTrooperRegion
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::TROOPER_ID,
        self::REGION_ID,
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
