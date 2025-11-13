<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperClub
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $club_id
 * @property string $identifier
 * @property bool $notify
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Club $club
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperClub extends Model
{
    const ID = 'id';
    const TROOPER_ID = 'trooper_id';
    const CLUB_ID = 'club_id';
    const IDENTIFIER = 'identifier';
    const NOTIFY = 'notify';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_trooper_clubs';

    protected $casts = [
        self::ID => 'int',
        self::TROOPER_ID => 'int',
        self::CLUB_ID => 'int',
        self::NOTIFY => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
