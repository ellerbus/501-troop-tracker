<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class 501stTrooper
 * 
 * @property int $legionid
 * @property string $name
 * @property string $thumbnail
 * @property string $link
 * @property int $squad
 * @property int $approved
 * @property int $status
 * @property int $standing
 * @property Carbon|null $joindate
 *
 * @package App\Models\Base
 */
class FiveOhFirstTrooper extends Model
{
    protected $table = '501st_troopers';
    protected $primaryKey = 'legionid';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'legionid' => 'int',
        'squad' => 'int',
        'approved' => 'int',
        'status' => 'int',
        'standing' => 'int',
        'joindate' => 'datetime'
    ];
}
