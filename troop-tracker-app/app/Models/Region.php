<?php

namespace App\Models;

use App\Models\Base\Region as BaseRegion;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasRegionScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends BaseRegion
{
    use HasRegionScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::ORGANIZATION_ID,
        self::NAME,
        self::ACTIVE,
        self::IMAGE_PATH_LG,
        self::IMAGE_PATH_SM,
    ];
}
