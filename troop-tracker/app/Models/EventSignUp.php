<?php

namespace App\Models;

use App\Models\Base\EventSignUp as BaseEventSignUp;

class EventSignUp extends BaseEventSignUp
{
	protected $fillable = [
		'trooperid',
		'troopid',
		'costume',
		'costume_backup',
		'status',
		'addedby',
		'note',
		'signuptime'
	];
}
