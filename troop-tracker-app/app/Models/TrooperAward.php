<?php

namespace App\Models;

use App\Models\Base\TrooperAward as BaseTrooperAward;
use App\Models\Scopes\HasTrooperAwardScopes;

class TrooperAward extends BaseTrooperAward
{
    use HasTrooperAwardScopes;

    protected $fillable = [
        self::TROOPER_ID,
        self::AWARD_ID
    ];
}
