<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationCheck
 * 
 * @property int $troopid
 * @property int $trooperid
 * @property int $commentid
 * @property int $trooperstatus
 * @property int $troopstatus
 *
 * @package App\Models\Base
 */
class NotificationCheck extends Model
{
    protected $table = 'notification_check';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'troopid' => 'int',
        'trooperid' => 'int',
        'commentid' => 'int',
        'trooperstatus' => 'int',
        'troopstatus' => 'int'
    ];
}
