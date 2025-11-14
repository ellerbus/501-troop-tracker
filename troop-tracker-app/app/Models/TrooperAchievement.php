<?php

namespace App\Models;

use App\Models\Base\TrooperAchievement as BaseTrooperAchievement;

class TrooperAchievement extends BaseTrooperAchievement
{
    protected $fillable = [
        self::TROOPER_ID,
        self::TROOPER_RANK,
        self::TROOPED_ALL_SQUADS,
        self::FIRST_TROOP_COMPLETED,
        self::TROOPED_10,
        self::TROOPED_25,
        self::TROOPED_50,
        self::TROOPED_75,
        self::TROOPED_100,
        self::TROOPED_150,
        self::TROOPED_200,
        self::TROOPED_250,
        self::TROOPED_300,
        self::TROOPED_400,
        self::TROOPED_500,
        self::TROOPED_501
    ];
}
