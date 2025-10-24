<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int $lastidtrooper
 * @property int $lastidevent
 * @property int $lastidlink
 * @property int $siteclosed
 * @property int $signupclosed
 * @property int $lastnotification
 * @property int $supportgoal
 * @property int $notifyevent
 * @property Carbon $syncdate
 * @property Carbon $syncdaterebels
 * @property string|null $sitemessage
 *
 * @package App\Models\Base
 */
class Setting extends Model
{
    protected $table = 'settings';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'lastidtrooper' => 'int',
        'lastidevent' => 'int',
        'lastidlink' => 'int',
        'siteclosed' => 'int',
        'signupclosed' => 'int',
        'lastnotification' => 'int',
        'supportgoal' => 'int',
        'notifyevent' => 'int',
        'syncdate' => 'datetime',
        'syncdaterebels' => 'datetime'
    ];
}
