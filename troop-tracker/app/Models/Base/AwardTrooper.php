<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AwardTrooper
 * 
 * @property int $id
 * @property int $trooperid
 * @property int $awardid
 * @property Carbon $awarded
 *
 * @package App\Models\Base
 */
class AwardTrooper extends Model
{
    protected $table = 'award_troopers';
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'awardid' => 'int',
        'awarded' => 'datetime'
    ];
}
