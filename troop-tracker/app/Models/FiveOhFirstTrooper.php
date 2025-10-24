<?php

namespace App\Models;

use App\Models\Base\FiveOhFirstTrooper as BaseFiveOhFirstTrooper;

class FiveOhFirstTrooper extends BaseFiveOhFirstTrooper
{
    protected $fillable = [
        'name',
        'thumbnail',
        'link',
        'squad',
        'approved',
        'status',
        'standing',
        'joindate'
    ];
}
