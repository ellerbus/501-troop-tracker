<?php

namespace App\Models;

use App\Models\Base\Costume as BaseCostume;

class Costume extends BaseCostume
{
	protected $fillable = [
		'costume',
		'club'
	];
}
