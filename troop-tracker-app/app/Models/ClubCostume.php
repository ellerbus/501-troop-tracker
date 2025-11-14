<?php

namespace App\Models;

use App\Models\Base\ClubCostume as BaseClubCostume;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClubCostume extends BaseClubCostume
{
    use HasFactory;

    protected $fillable = [
        self::CLUB_ID,
        self::NAME
    ];

    public function fullCostumeName(): string
    {
        return "({$this->club->name}) {$this->name}";
    }
}
