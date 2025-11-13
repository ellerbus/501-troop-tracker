<?php

namespace App\Models;

use App\Models\Base\Club as BaseClub;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends BaseClub
{
    use HasFactory;

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
}
