<?php

namespace App\Models;

use App\Models\Base\FiveOhFirstCostume as BaseFiveOhFirstCostume;

class FiveOhFirstCostume extends BaseFiveOhFirstCostume
{
    protected $fillable = [
        'legionid',
        'costumeid',
        'prefix',
        'costumename',
        'photo',
        'thumbnail',
        'bucketoff'
    ];
}
