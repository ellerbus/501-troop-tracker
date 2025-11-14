<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventClub
 * 
 * @property int $id
 * @property int $event_id
 * @property int $club_id
 * @property int $troopers_allowed
 * @property int $handlers_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club $club
 * @property Event $event
 *
 * @package App\Models\Base
 */
class EventClub extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const CLUB_ID = 'club_id';
    const TROOPERS_ALLOWED = 'troopers_allowed';
    const HANDLERS_ALLOWED = 'handlers_allowed';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_event_clubs';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::CLUB_ID => 'int',
        self::TROOPERS_ALLOWED => 'int',
        self::HANDLERS_ALLOWED => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
