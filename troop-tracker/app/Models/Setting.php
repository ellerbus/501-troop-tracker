<?php

namespace App\Models;

use App\Models\Base\Setting as BaseSetting;

class Setting extends BaseSetting
{
	protected $fillable = [
		'lastidtrooper',
		'lastidevent',
		'lastidlink',
		'siteclosed',
		'signupclosed',
		'lastnotification',
		'supportgoal',
		'notifyevent',
		'syncdate',
		'syncdaterebels',
		'sitemessage'
	];
}
