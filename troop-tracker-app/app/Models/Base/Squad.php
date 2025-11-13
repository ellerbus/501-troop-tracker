<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Trooper;
use App\Models\TrooperSquad;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Squad
 * 
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property string|null $image_path_lg
 * @property string|null $image_path_sm
 * @property bool $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club $club
 * @property Collection|Trooper[] $troopers
 *
 * @package App\Models\Base
 */
class Squad extends Model
{
    const ID = 'id';
    const CLUB_ID = 'club_id';
    const NAME = 'name';
    const IMAGE_PATH_LG = 'image_path_lg';
    const IMAGE_PATH_SM = 'image_path_sm';
    const ACTIVE = 'active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_squads';

    protected $casts = [
        self::ID => 'int',
        self::CLUB_ID => 'int',
        self::ACTIVE => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function troopers()
    {
        return $this->belongsToMany(Trooper::class, 'tt_trooper_squads')
                    ->withPivot(TrooperSquad::ID, TrooperSquad::NOTIFY, TrooperSquad::STATUS)
                    ->withTimestamps();
    }
}
