<?php

namespace App\Models;

use App\Enums\TrooperEventStatus;
use App\Models\Base\EventTrooper as BaseEventTrooper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventTrooper extends BaseEventTrooper
{
    use HasFactory;

    protected $fillable = [
        self::EVENT_ID,
        self::TROOPER_ID,
        self::CLUB_COSTUME_ID,
        self::BACKUP_CLUB_COSTUME_ID,
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
