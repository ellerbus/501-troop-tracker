<?php

namespace App\Models;

use App\Models\Base\SgTrooper as BaseSgTrooper;

class SgTrooper extends BaseSgTrooper
{
	protected $fillable = [
		'sgid',
		'name',
		'image',
		'link',
		'costumename',
		'ranktitle'
	];
}
