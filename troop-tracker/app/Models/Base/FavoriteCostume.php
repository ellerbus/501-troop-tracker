<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteCostume
 * 
 * @property int $trooperid
 * @property int $costumeid
 *
 * @package App\Models\Base
 */
class FavoriteCostume extends Model
{
    protected $table = 'favorite_costumes';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'costumeid' => 'int'
    ];
}
