<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventNotification
 * 
 * @property int $troopid
 * @property int $trooperid
 *
 * @package App\Models\Base
 */
class EventNotification extends Model
{
    protected $table = 'event_notifications';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'troopid' => 'int',
        'trooperid' => 'int'
    ];
}
