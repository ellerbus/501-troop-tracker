<?php

namespace App\Models;

use App\Models\Base\Title as BaseTitle;

class Title extends BaseTitle
{
	protected $fillable = [
		'title',
		'icon',
		'forum_id'
	];
}
