<?php

namespace App\Models;

use App\Models\Base\Event as BaseEvent;

class Event extends BaseEvent
{
	protected $fillable = [
		self::NAME,
		self::STARTS_AT,
		self::ENDS_AT,
		self::LIMIT_PARTICIPANTS,
		self::TOTAL_TROOPERS_ALLOWED,
		self::TOTAL_HANDLERS_ALLOWED,
		self::SQUAD_ID
	];
}
