<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RebelCostume
 * 
 * @property string $rebelid
 * @property string $costumename
 * @property string $costumeimage
 *
 * @package App\Models\Base
 */
class RebelCostume extends Model
{
    protected $table = 'rebel_costumes';
    public $incrementing = false;
    public $timestamps = false;
}
