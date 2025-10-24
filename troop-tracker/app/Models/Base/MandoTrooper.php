<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MandoTrooper
 * 
 * @property int $mandoid
 * @property string $name
 * @property string $costume
 *
 * @package App\Models\Base
 */
class MandoTrooper extends Model
{
    protected $table = 'mando_troopers';
    protected $primaryKey = 'mandoid';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'mandoid' => 'int'
    ];
}
