<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SgTrooper
 * 
 * @property string $sgid
 * @property string $name
 * @property string $image
 * @property string $link
 * @property string $costumename
 * @property string $ranktitle
 *
 * @package App\Models\Base
 */
class SgTrooper extends Model
{
    protected $table = 'sg_troopers';
    public $incrementing = false;
    public $timestamps = false;
}
