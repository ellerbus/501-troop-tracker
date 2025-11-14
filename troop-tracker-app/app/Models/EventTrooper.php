<?php

namespace App\Models;

use App\Models\Base\EventTrooper as BaseEventTrooper;

class EventTrooper extends BaseEventTrooper
{
	protected $fillable = [
		self::EVENT_ID,
		self::TROOPER_ID,
		self::COSTUME_ID,
		self::BACKUP_COSTUME_ID,
		self::ADDED_BY_TROOPER_ID,
		self::STATUS
	];
}
