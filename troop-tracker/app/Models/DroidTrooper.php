<?php

namespace App\Models;

use App\Models\Base\DroidTrooper as BaseDroidTrooper;

class DroidTrooper extends BaseDroidTrooper
{
	protected $fillable = [
		'forum_id',
		'droidname',
		'imageurl'
	];
}
