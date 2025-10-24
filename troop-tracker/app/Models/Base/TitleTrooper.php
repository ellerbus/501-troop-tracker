<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TitleTrooper
 * 
 * @property int $id
 * @property int $trooperid
 * @property int $titleid
 * @property Carbon $datetime
 *
 * @package App\Models\Base
 */
class TitleTrooper extends Model
{
    protected $table = 'title_troopers';
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'titleid' => 'int',
        'datetime' => 'datetime'
    ];
}
