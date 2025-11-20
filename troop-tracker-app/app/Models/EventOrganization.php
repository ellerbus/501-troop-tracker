<?php

namespace App\Models;

use App\Models\Base\EventOrganization as BaseEventOrganization;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventOrganization extends BaseEventOrganization
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::EVENT_ID,
        self::ORGANIZATION_ID,
        self::TROOPERS_ALLOWED,
        self::HANDLERS_ALLOWED,
    ];
}
