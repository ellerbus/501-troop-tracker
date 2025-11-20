<?php

namespace App\Models;

use App\Models\Base\EventRegion as BaseEventRegion;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventRegion extends BaseEventRegion
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::EVENT_ID,
        self::REGION_ID,
        self::TROOPERS_ALLOWED,
        self::HANDLERS_ALLOWED,
    ];
}
