<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RebelTrooper
 * 
 * @property string $rebelid
 * @property string $name
 * @property string $rebelforum
 *
 * @package App\Models\Base
 */
class RebelTrooper extends Model
{
    protected $table = 'rebel_troopers';
    public $incrementing = false;
    public $timestamps = false;
}
