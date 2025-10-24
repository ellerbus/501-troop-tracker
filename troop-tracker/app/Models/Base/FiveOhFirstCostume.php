<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class 501stCostume
 * 
 * @property int $legionid
 * @property int $costumeid
 * @property string $prefix
 * @property string $costumename
 * @property string $photo
 * @property string $thumbnail
 * @property string $bucketoff
 *
 * @package App\Models\Base
 */
class FiveOhFirstCostume extends Model
{
    protected $table = '501st_costumes';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'legionid' => 'int',
        'costumeid' => 'int'
    ];
}
