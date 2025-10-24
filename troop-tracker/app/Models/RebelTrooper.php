<?php

namespace App\Models;

use App\Models\Base\RebelTrooper as BaseRebelTrooper;

class RebelTrooper extends BaseRebelTrooper
{
	protected $fillable = [
		'rebelid',
		'name',
		'rebelforum'
	];
}
