<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Costume;
use App\Models\Event;
use App\Models\EventOrganization;
use App\Models\Region;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $identifier_display
 * @property string|null $identifier_validation
 * @property bool $active
 * @property string|null $image_path_lg
 * @property string|null $image_path_sm
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Collection|Costume[] $costumes
 * @property Collection|Event[] $events
 * @property Collection|Region[] $regions
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Organization extends Model
{
    const ID = 'id';
    const NAME = 'name';
    const SLUG = 'slug';
    const IDENTIFIER_DISPLAY = 'identifier_display';
    const IDENTIFIER_VALIDATION = 'identifier_validation';
    const ACTIVE = 'active';
    const IMAGE_PATH_LG = 'image_path_lg';
    const IMAGE_PATH_SM = 'image_path_sm';
    const DESCRIPTION = 'description';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_organizations';

    protected $casts = [
        self::ID => 'int',
        self::ACTIVE => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function costumes()
    {
        return $this->hasMany(Costume::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tt_event_organizations')
                    ->withPivot(EventOrganization::ID, EventOrganization::TROOPERS_ALLOWED, EventOrganization::HANDLERS_ALLOWED, EventOrganization::CREATED_ID, EventOrganization::UPDATED_ID)
                    ->withTimestamps();
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_organizations')
                    ->withPivot(TrooperOrganization::ID, TrooperOrganization::IDENTIFIER, TrooperOrganization::NOTIFY, TrooperOrganization::STATUS, TrooperOrganization::CREATED_ID, TrooperOrganization::UPDATED_ID)
                    ->withTimestamps();
    }
}
