<?php

namespace App\Models;

use App\Models\Base\MandoCostume as BaseMandoCostume;

class MandoCostume extends BaseMandoCostume
{
	protected $fillable = [
		'mandoid',
		'costumeurl'
	];
}
