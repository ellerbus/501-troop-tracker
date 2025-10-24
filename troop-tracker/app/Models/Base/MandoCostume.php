<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MandoCostume
 * 
 * @property int $mandoid
 * @property string $costumeurl
 *
 * @package App\Models\Base
 */
class MandoCostume extends Model
{
    protected $table = 'mando_costumes';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'mandoid' => 'int'
    ];
}
