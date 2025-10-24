<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Upload
 * 
 * @property int $id
 * @property int $troopid
 * @property int $trooperid
 * @property string|null $filename
 * @property int $admin
 * @property Carbon $date
 *
 * @package App\Models\Base
 */
class Upload extends Model
{
    protected $table = 'uploads';
    public $timestamps = false;

    protected $casts = [
        'troopid' => 'int',
        'trooperid' => 'int',
        'admin' => 'int',
        'date' => 'datetime'
    ];
}
