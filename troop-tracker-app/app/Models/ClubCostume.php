<?php

namespace App\Models;

use App\Models\Base\ClubCostume as BaseClubCostume;

class ClubCostume extends BaseClubCostume
{
	protected $fillable = [
		self::CLUB_ID,
		self::NAME
	];
}
