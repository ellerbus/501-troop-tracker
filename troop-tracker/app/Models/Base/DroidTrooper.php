<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DroidTrooper
 * 
 * @property string $forum_id
 * @property string $droidname
 * @property string $imageurl
 *
 * @package App\Models\Base
 */
class DroidTrooper extends Model
{
    protected $table = 'droid_troopers';
    public $incrementing = false;
    public $timestamps = false;
}
