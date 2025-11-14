<?php

namespace App\Models;

use App\Models\Base\EventCostume as BaseEventCostume;

class EventCostume extends BaseEventCostume
{
	protected $fillable = [
		self::EVENT_ID,
		self::COSTUME_ID,
		self::REQUESTED,
		self::EXCLUDED
	];
}
