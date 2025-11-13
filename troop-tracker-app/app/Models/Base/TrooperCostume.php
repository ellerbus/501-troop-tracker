<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ClubCostume;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperCostume
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $costume_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ClubCostume $club_costume
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperCostume extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const COSTUME_ID = 'costume_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_trooper_costumes';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::COSTUME_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club_costume()
    {
        return $this->belongsTo(ClubCostume::class, \App\Models\TrooperCostume::COSTUME_ID);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
