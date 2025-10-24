<?php

namespace App\Models;

use App\Models\Base\Tagged as BaseTagged;

class Tagged extends BaseTagged
{
	protected $fillable = [
		'photoid',
		'trooperid'
	];
}
