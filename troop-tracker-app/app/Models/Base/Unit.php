<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Event;
use App\Models\EventUnit;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\TrooperUnit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Unit
 * 
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property bool $active
 * @property string|null $image_path_lg
 * @property string|null $image_path_sm
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Region $region
 * @property Collection|Event[] $events
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Unit extends Model
{
    const ID = 'id';
    const REGION_ID = 'region_id';
    const NAME = 'name';
    const ACTIVE = 'active';
    const IMAGE_PATH_LG = 'image_path_lg';
    const IMAGE_PATH_SM = 'image_path_sm';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_units';

    protected $casts = [
        self::ID => 'int',
        self::REGION_ID => 'int',
        self::ACTIVE => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tt_event_units')
                    ->withPivot(EventUnit::ID, EventUnit::TROOPERS_ALLOWED, EventUnit::HANDLERS_ALLOWED, EventUnit::CREATED_ID, EventUnit::UPDATED_ID)
                    ->withTimestamps();
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_units')
                    ->withPivot(TrooperUnit::ID, TrooperUnit::NOTIFY, TrooperUnit::MEMBERSHIP_STATUS, TrooperUnit::MEMBERSHIP_ROLE, TrooperUnit::CREATED_ID, TrooperUnit::UPDATED_ID)
                    ->withTimestamps();
    }
}
