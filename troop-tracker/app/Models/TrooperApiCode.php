<?php

namespace App\Models;

use App\Models\Base\TrooperApiCode as BaseTrooperApiCode;

class TrooperApiCode extends BaseTrooperApiCode
{
	protected $fillable = [
		'trooperid',
		'api_code',
		'date_created'
	];
}
