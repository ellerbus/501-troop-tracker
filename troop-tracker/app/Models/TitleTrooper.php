<?php

namespace App\Models;

use App\Models\Base\TitleTrooper as BaseTitleTrooper;

class TitleTrooper extends BaseTitleTrooper
{
	protected $fillable = [
		'trooperid',
		'titleid',
		'datetime'
	];
}
