<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Squad;
use App\Models\Trooper;
use App\Models\TrooperClub;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Club
 * 
 * @property int $id
 * @property string $name
 * @property string|null $image_path_lg
 * @property string|null $image_path_sm
 * @property string|null $identifier_display
 * @property string|null $identifier_validation
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Squad[] $squads
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Club extends Model
{
    const ID = 'id';
    const NAME = 'name';
    const IMAGE_PATH_LG = 'image_path_lg';
    const IMAGE_PATH_SM = 'image_path_sm';
    const IDENTIFIER_DISPLAY = 'identifier_display';
    const IDENTIFIER_VALIDATION = 'identifier_validation';
    const ACTIVE = 'active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_clubs';

    protected $casts = [
        self::ID => 'int',
        self::ACTIVE => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function squads()
    {
        return $this->hasMany(Squad::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_clubs')
                    ->withPivot(TrooperClub::ID, TrooperClub::IDENTIFIER, TrooperClub::NOTIFY, TrooperClub::STATUS)
                    ->withTimestamps();
    }
}
