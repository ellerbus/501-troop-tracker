<?php

namespace App\Models;

use App\Models\Base\MobileApp as BaseMobileApp;

class MobileApp extends BaseMobileApp
{
	protected $fillable = [
		'userid',
		'fcm',
		'date_created'
	];
}
