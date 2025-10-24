<?php

namespace App\Models;

use App\Models\Base\NotificationCheck as BaseNotificationCheck;

class NotificationCheck extends BaseNotificationCheck
{
	protected $fillable = [
		'troopid',
		'trooperid',
		'commentid',
		'trooperstatus',
		'troopstatus'
	];
}
