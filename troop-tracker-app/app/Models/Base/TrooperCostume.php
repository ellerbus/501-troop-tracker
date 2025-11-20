<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Costume;
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
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Costume $costume
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
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_trooper_costumes';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::COSTUME_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function costume()
    {
        return $this->belongsTo(Costume::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
