<?php

namespace App\Models;

use App\Models\Base\Setting as BaseSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseSetting
{
    use HasFactory;

    protected $fillable = [
        self::SITE_CLOSED,
        self::SUPPORT_GOAL
    ];
}
