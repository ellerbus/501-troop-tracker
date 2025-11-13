<?php

namespace App\Models;

use App\Models\Base\TrooperCostume as BaseTrooperCostume;

class TrooperCostume extends BaseTrooperCostume
{
	protected $fillable = [
		self::TROOPER_ID,
		self::COSTUME_ID
	];
}
