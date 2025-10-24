<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TrooperApiCode
 * 
 * @property int $id
 * @property int $trooperid
 * @property string $api_code
 * @property Carbon $date_created
 *
 * @package App\Models\Base
 */
class TrooperApiCode extends Model
{
    protected $table = 'trooper_api_codes';
    public $timestamps = false;

    protected $casts = [
        'trooperid' => 'int',
        'date_created' => 'datetime'
    ];
}
