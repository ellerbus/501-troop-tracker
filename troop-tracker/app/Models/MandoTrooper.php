<?php

namespace App\Models;

use App\Models\Base\MandoTrooper as BaseMandoTrooper;

class MandoTrooper extends BaseMandoTrooper
{
	protected $fillable = [
		'name',
		'costume'
	];
}
