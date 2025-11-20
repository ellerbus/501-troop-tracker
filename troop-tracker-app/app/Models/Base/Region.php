<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Event;
use App\Models\EventRegion;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperRegion;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Region
 * 
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property bool $active
 * @property string|null $image_path_lg
 * @property string|null $image_path_sm
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Organization $organization
 * @property Collection|Event[] $events
 * @property Collection|Trooper[] $troopers
 * @property Collection|Unit[] $units
 *
 * @package App\Models\Base
 */
class Region extends Model
{
    const ID = 'id';
    const ORGANIZATION_ID = 'organization_id';
    const NAME = 'name';
    const ACTIVE = 'active';
    const IMAGE_PATH_LG = 'image_path_lg';
    const IMAGE_PATH_SM = 'image_path_sm';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_regions';

    protected $casts = [
        self::ID => 'int',
        self::ORGANIZATION_ID => 'int',
        self::ACTIVE => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tt_event_regions')
                    ->withPivot(EventRegion::ID, EventRegion::TROOPERS_ALLOWED, EventRegion::HANDLERS_ALLOWED, EventRegion::CREATED_ID, EventRegion::UPDATED_ID)
                    ->withTimestamps();
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_regions')
                    ->withPivot(TrooperRegion::ID, TrooperRegion::NOTIFY, TrooperRegion::STATUS, TrooperRegion::CREATED_ID, TrooperRegion::UPDATED_ID)
                    ->withTimestamps();
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
