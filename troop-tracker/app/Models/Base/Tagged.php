<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tagged
 * 
 * @property int $id
 * @property int $photoid
 * @property int $trooperid
 *
 * @package App\Models\Base
 */
class Tagged extends Model
{
    protected $table = 'tagged';
    public $timestamps = false;

    protected $casts = [
        'photoid' => 'int',
        'trooperid' => 'int'
    ];
}
