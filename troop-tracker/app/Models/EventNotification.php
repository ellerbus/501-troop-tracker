<?php

namespace App\Models;

use App\Models\Base\EventNotification as BaseEventNotification;

class EventNotification extends BaseEventNotification
{
	protected $fillable = [
		'troopid',
		'trooperid'
	];
}
