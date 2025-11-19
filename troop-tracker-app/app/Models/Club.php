<?php

namespace App\Models;

use App\Models\Base\Club as BaseClub;
use App\Models\Scopes\HasClubScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class Club extends BaseClub
{
    use HasFactory;
    use HasClubScopes;

    protected $fillable = [
        self::NAME,
        self::IMAGE_PATH_LG,
        self::IMAGE_PATH_SM,
        self::IDENTIFIER_DISPLAY,
        self::IDENTIFIER_VALIDATION,
        self::ACTIVE
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
        ]);
    }

    /**
     * Get all of the event troopers for the club through its costumes.
     */
    public function event_troopers(): HasManyThrough
    {
        return $this->hasManyThrough(EventTrooper::class, ClubCostume::class);
    }
}

