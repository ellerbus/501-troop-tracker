<?php

namespace App\Models;

use App\Models\Base\Squad as BaseSquad;
use App\Models\Scopes\HasSquadScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Squad extends BaseSquad
{
    use HasFactory;
    use HasSquadScopes;

    protected $fillable = [
        self::CLUB_ID,
        self::NAME,
        self::IMAGE_PATH_LG,
        self::IMAGE_PATH_SM,
        self::ACTIVE
    ];

    protected function casts(): array
    {
        return array_merge($this->casts, [
        ]);
    }
}
