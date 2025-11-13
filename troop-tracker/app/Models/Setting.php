<?php

namespace App\Models;

use App\Models\Base\Setting as BaseSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseSetting
{
    use HasFactory;

    protected $fillable = [
        'lastidtrooper',
        'lastidevent',
        'lastidlink',
        'siteclosed',
        'signupclosed',
        'lastnotification',
        'supportgoal',
        'notifyevent',
        'syncdate',
        'syncdaterebels',
        'sitemessage'
    ];
}
