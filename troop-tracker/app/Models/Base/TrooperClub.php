<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Trooper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperClub
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $club_id
 * @property string $identifier
 * @property bool $notify
 * @property int $membership_status
 * 
 * @property Club $club
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperClub extends Model
{
    protected $table = 'trooper_clubs';
    public $timestamps = false;

    protected $casts = [
        'trooper_id' => 'int',
        'club_id' => 'int',
        'notify' => 'bool',
        'membership_status' => 'int'
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
