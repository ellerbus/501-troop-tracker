<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Trooper;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperUnit
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $unit_id
 * @property bool $notify
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Trooper $trooper
 * @property Unit $unit
 *
 * @package App\Models\Base
 */
class TrooperUnit extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const UNIT_ID = 'unit_id';
    const NOTIFY = 'notify';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_trooper_units';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::UNIT_ID => 'int',
        self::NOTIFY => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
