<?php

namespace App\Models;

use App\Models\Base\EventUnit as BaseEventUnit;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventUnit extends BaseEventUnit
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::EVENT_ID,
        self::UNIT_ID,
        self::TROOPERS_ALLOWED,
        self::HANDLERS_ALLOWED,
        self::CREATED_ID,
        self::UPDATED_ID
    ];
}
