<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ClubCostume;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventCostume
 * 
 * @property int $id
 * @property int $event_id
 * @property int $club_costume_id
 * @property bool $requested
 * @property bool $excluded
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ClubCostume $club_costume
 * @property Event $event
 *
 * @package App\Models\Base
 */
class EventCostume extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const CLUB_COSTUME_ID = 'club_costume_id';
    const REQUESTED = 'requested';
    const EXCLUDED = 'excluded';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_event_costumes';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::CLUB_COSTUME_ID => 'int',
        self::REQUESTED => 'bool',
        self::EXCLUDED => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club_costume()
    {
        return $this->belongsTo(ClubCostume::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
