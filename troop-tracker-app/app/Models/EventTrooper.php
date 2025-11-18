<?php

namespace App\Models;

use App\Enums\TrooperEventStatus;
use App\Models\Base\EventTrooper as BaseEventTrooper;
use App\Models\Scopes\HasEventTrooperScopes;

class EventTrooper extends BaseEventTrooper
{
    use HasEventTrooperScopes;

    protected $fillable = [
        self::EVENT_ID,
        self::TROOPER_ID,
        self::COSTUME_ID,
        self::BACKUP_COSTUME_ID,
        self::ADDED_BY_TROOPER_ID,
        self::STATUS
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
            self::STATUS => TrooperEventStatus::class,
        ]);
    }
}
