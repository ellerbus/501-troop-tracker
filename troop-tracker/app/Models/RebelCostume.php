<?php

namespace App\Models;

use App\Models\Base\RebelCostume as BaseRebelCostume;

class RebelCostume extends BaseRebelCostume
{
	protected $fillable = [
		'rebelid',
		'costumename',
		'costumeimage'
	];
}
