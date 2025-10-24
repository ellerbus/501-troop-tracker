<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MobileApp
 * 
 * @property int $id
 * @property int $userid
 * @property string $fcm
 * @property Carbon $date_created
 *
 * @package App\Models\Base
 */
class MobileApp extends Model
{
    protected $table = 'mobile_app';
    public $timestamps = false;

    protected $casts = [
        'userid' => 'int',
        'date_created' => 'datetime'
    ];
}
