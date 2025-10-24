<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventSignUp
 * 
 * @property int $id
 * @property int|null $trooperid
 * @property int $troopid
 * @property int|null $costume
 * @property int $costume_backup
 * @property int $status
 * @property int $addedby
 * @property string $note
 * @property Carbon $signuptime
 *
 * @package App\Models\Base
 */
class EventSignUp extends Model
{
    protected $table = 'event_sign_up';
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'troopid' => 'int',
        'costume' => 'int',
        'costume_backup' => 'int',
        'status' => 'int',
        'addedby' => 'int',
        'signuptime' => 'datetime'
    ];
}
