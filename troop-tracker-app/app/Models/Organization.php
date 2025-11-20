<?php

namespace App\Models;

use App\Models\Base\Organization as BaseOrganization;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasOrganizationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Organization extends BaseOrganization
{
    use HasOrganizationScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::NAME,
        self::SLUG,
        self::IDENTIFIER_DISPLAY,
        self::IDENTIFIER_VALIDATION,
        self::ACTIVE,
        self::IMAGE_PATH_LG,
        self::IMAGE_PATH_SM,
        self::DESCRIPTION,
    ];

    /**
     * Get all of the event troopers for the organization through its costumes.
     */
    public function event_troopers(): HasManyThrough
    {
        return $this->hasManyThrough(EventTrooper::class, Costume::class);
    }
}
