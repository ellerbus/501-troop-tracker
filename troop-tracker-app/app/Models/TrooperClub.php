<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperClub as BaseTrooperClub;

class TrooperClub extends BaseTrooperClub
{
    protected $fillable = [
        self::TROOPER_ID,
        self::CLUB_ID,
        self::NOTIFY,
        self::STATUS,
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
            self::STATUS => MembershipStatus::class,
        ]);
    }
}
