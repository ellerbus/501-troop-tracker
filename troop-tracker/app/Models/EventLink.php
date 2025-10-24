<?php

namespace App\Models;

use App\Models\Base\EventLink as BaseEventLink;

class EventLink extends BaseEventLink
{
	protected $fillable = [
		'name',
		'allowed_sign_ups',
		'created'
	];
}
