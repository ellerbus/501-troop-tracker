<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperSquad as BaseTrooperSquad;

class TrooperSquad extends BaseTrooperSquad
{
    protected $fillable = [
        self::TROOPER_ID,
        self::SQUAD_ID,
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
