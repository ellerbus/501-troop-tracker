<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventLink
 * 
 * @property int $id
 * @property string $name
 * @property int $allowed_sign_ups
 * @property Carbon $created
 *
 * @package App\Models\Base
 */
class EventLink extends Model
{
    protected $table = 'event_link';
    public $timestamps = false;

    protected $casts = [
        'allowed_sign_ups' => 'int',
        'created' => 'datetime'
    ];
}
