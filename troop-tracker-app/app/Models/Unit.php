<?php

namespace App\Models;

use App\Models\Base\Unit as BaseUnit;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasUnitScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends BaseUnit
{
    use HasUnitScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::REGION_ID,
        self::NAME,
        self::ACTIVE,
    ];
}
