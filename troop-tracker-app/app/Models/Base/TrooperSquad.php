<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Squad;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperSquad
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $squad_id
 * @property bool $notify
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Squad $squad
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperSquad extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const SQUAD_ID = 'squad_id';
    const NOTIFY = 'notify';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_trooper_squads';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::SQUAD_ID => 'int',
        self::NOTIFY => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
