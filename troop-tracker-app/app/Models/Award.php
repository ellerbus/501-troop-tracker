<?php

namespace App\Models;

use App\Models\Base\Award as BaseAward;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Award extends BaseAward
{
    use HasFactory;

    protected $fillable = [
        self::NAME
    ];
}
