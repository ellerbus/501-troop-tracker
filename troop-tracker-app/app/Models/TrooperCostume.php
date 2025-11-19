<?php

namespace App\Models;

use App\Models\Base\TrooperCostume as BaseTrooperCostume;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperCostume extends BaseTrooperCostume
{
    use HasFactory;

    protected $fillable = [
        self::TROOPER_ID,
        self::CLUB_COSTUME_ID
    ];
}
