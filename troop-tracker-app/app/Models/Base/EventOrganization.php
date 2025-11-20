<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Event;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventOrganization
 * 
 * @property int $id
 * @property int $event_id
 * @property int $organization_id
 * @property int $troopers_allowed
 * @property int $handlers_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Event $event
 * @property Organization $organization
 *
 * @package App\Models\Base
 */
class EventOrganization extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const ORGANIZATION_ID = 'organization_id';
    const TROOPERS_ALLOWED = 'troopers_allowed';
    const HANDLERS_ALLOWED = 'handlers_allowed';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_event_organizations';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::ORGANIZATION_ID => 'int',
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
