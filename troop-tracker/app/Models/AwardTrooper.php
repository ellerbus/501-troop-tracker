<?php

namespace App\Models;

use App\Models\Base\AwardTrooper as BaseAwardTrooper;

class AwardTrooper extends BaseAwardTrooper
{
	protected $fillable = [
		'trooperid',
		'awardid',
		'awarded'
	];
}
