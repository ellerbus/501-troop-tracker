<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Costume;
use App\Models\EventCostume;
use App\Models\EventOrganization;
use App\Models\EventRegion;
use App\Models\EventTrooper;
use App\Models\EventUnit;
use App\Models\EventUpload;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property bool $closed
 * @property int $charity_direct_funds
 * @property int $charity_indirect_funds
 * @property string|null $charity_name
 * @property int|null $charity_hours
 * @property bool $limit_participants
 * @property int|null $total_troopers_allowed
 * @property int|null $total_handlers_allowed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Collection|Costume[] $costumes
 * @property Collection|Organization[] $organizations
 * @property Collection|Region[] $regions
 * @property Collection|Trooper[] $troopers
 * @property Collection|Unit[] $units
 * @property Collection|EventUpload[] $event_uploads
 *
 * @package App\Models\Base
 */
class Event extends Model
{
    const ID = 'id';
    const NAME = 'name';
    const STARTS_AT = 'starts_at';
    const ENDS_AT = 'ends_at';
    const CLOSED = 'closed';
    const CHARITY_DIRECT_FUNDS = 'charity_direct_funds';
    const CHARITY_INDIRECT_FUNDS = 'charity_indirect_funds';
    const CHARITY_NAME = 'charity_name';
    const CHARITY_HOURS = 'charity_hours';
    const LIMIT_PARTICIPANTS = 'limit_participants';
    const TOTAL_TROOPERS_ALLOWED = 'total_troopers_allowed';
    const TOTAL_HANDLERS_ALLOWED = 'total_handlers_allowed';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_events';

    protected $casts = [
        self::ID => 'int',
        self::STARTS_AT => 'datetime',
        self::ENDS_AT => 'datetime',
        self::CLOSED => 'bool',
        self::CHARITY_DIRECT_FUNDS => 'int',
        self::CHARITY_INDIRECT_FUNDS => 'int',
        self::CHARITY_HOURS => 'int',
        self::LIMIT_PARTICIPANTS => 'bool',
        self::TOTAL_TROOPERS_ALLOWED => 'int',
        self::TOTAL_HANDLERS_ALLOWED => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function costumes()
    {
        return $this->belongsToMany(Costume::class, 'tt_event_costumes')
                    ->withPivot(EventCostume::ID, EventCostume::REQUESTED, EventCostume::EXCLUDED, EventCostume::CREATED_ID, EventCostume::UPDATED_ID)
                    ->withTimestamps();
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'tt_event_organizations')
                    ->withPivot(EventOrganization::ID, EventOrganization::TROOPERS_ALLOWED, EventOrganization::HANDLERS_ALLOWED, EventOrganization::CREATED_ID, EventOrganization::UPDATED_ID)
                    ->withTimestamps();
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'tt_event_regions')
                    ->withPivot(EventRegion::ID, EventRegion::TROOPERS_ALLOWED, EventRegion::HANDLERS_ALLOWED, EventRegion::CREATED_ID, EventRegion::UPDATED_ID)
                    ->withTimestamps();
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_event_troopers')
                    ->withPivot(EventTrooper::ID, EventTrooper::COSTUME_ID, EventTrooper::BACKUP_COSTUME_ID, EventTrooper::ADDED_BY_TROOPER_ID, EventTrooper::STATUS, EventTrooper::CREATED_ID, EventTrooper::UPDATED_ID)
                    ->withTimestamps();
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'tt_event_units')
                    ->withPivot(EventUnit::ID, EventUnit::TROOPERS_ALLOWED, EventUnit::HANDLERS_ALLOWED, EventUnit::CREATED_ID, EventUnit::UPDATED_ID)
                    ->withTimestamps();
    }

    public function event_uploads()
    {
        return $this->hasMany(EventUpload::class);
    }
}
