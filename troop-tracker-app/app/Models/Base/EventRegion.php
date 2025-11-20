<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Event;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventRegion
 * 
 * @property int $id
 * @property int $event_id
 * @property int $region_id
 * @property int $troopers_allowed
 * @property int $handlers_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Event $event
 * @property Region $region
 *
 * @package App\Models\Base
 */
class EventRegion extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const REGION_ID = 'region_id';
    const TROOPERS_ALLOWED = 'troopers_allowed';
    const HANDLERS_ALLOWED = 'handlers_allowed';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_event_regions';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::REGION_ID => 'int',
        self::TROOPERS_ALLOWED => 'int',
        self::HANDLERS_ALLOWED => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
