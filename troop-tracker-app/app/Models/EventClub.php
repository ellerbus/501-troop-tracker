<?php

namespace App\Models;

use App\Models\Base\EventClub as BaseEventClub;

class EventClub extends BaseEventClub
{
	protected $fillable = [
		self::EVENT_ID,
		self::CLUB_ID,
		self::TROOPERS_ALLOWED,
		self::HANDLERS_ALLOWED
	];
}
