<?php

namespace App\Models;

use App\Models\Base\Notification as BaseNotification;

class Notification extends BaseNotification
{
	protected $fillable = [
		'message',
		'trooperid',
		'type',
		'json',
		'datetime'
	];
}
