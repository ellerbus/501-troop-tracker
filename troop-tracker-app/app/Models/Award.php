<?php

namespace App\Models;

use App\Models\Base\Award as BaseAward;

class Award extends BaseAward
{
	protected $fillable = [
		self::NAME
	];
}
