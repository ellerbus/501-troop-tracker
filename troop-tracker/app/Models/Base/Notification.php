<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property string $message
 * @property int $trooperid
 * @property int $type
 * @property string|null $json
 * @property Carbon $datetime
 *
 * @package App\Models\Base
 */
class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'type' => 'int',
        'datetime' => 'datetime'
    ];
}
