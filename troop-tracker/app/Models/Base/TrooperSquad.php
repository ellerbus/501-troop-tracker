<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Squad;
use App\Models\Trooper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperSquad
 * 
 * @property int $id
 * @property int $trooper_id
 * @property int $squad_id
 * @property bool $notify
 * @property int $membership_status
 * 
 * @property Squad $squad
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class TrooperSquad extends Model
{
    protected $table = 'trooper_squads';
    public $timestamps = false;

    protected $casts = [
        'trooper_id' => 'int',
        'squad_id' => 'int',
        'notify' => 'bool',
        'membership_status' => 'int'
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
