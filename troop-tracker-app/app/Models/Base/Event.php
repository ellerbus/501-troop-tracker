<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\EventClub;
use App\Models\EventCostume;
use App\Models\EventTrooper;
use App\Models\EventUpload;
use App\Models\Squad;
use App\Models\Trooper;
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
 * @property int|null $squad_id
 * 
 * @property Squad|null $squad
 * @property Collection|Club[] $clubs
 * @property Collection|EventCostume[] $event_costumes
 * @property Collection|Trooper[] $troopers
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
    const SQUAD_ID = 'squad_id';
    protected $table = 'tt_events';
    public $timestamps = false;

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
        self::SQUAD_ID => 'int'
    ];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'tt_event_clubs')
                    ->withPivot(EventClub::ID, EventClub::TROOPERS_ALLOWED, EventClub::HANDLERS_ALLOWED)
                    ->withTimestamps();
    }

    public function event_costumes()
    {
        return $this->hasMany(EventCostume::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_event_troopers')
                    ->withPivot(EventTrooper::ID, EventTrooper::COSTUME_ID, EventTrooper::BACKUP_COSTUME_ID, EventTrooper::ADDED_BY_TROOPER_ID, EventTrooper::STATUS)
                    ->withTimestamps();
    }

    public function event_uploads()
    {
        return $this->hasMany(EventUpload::class);
    }
}
