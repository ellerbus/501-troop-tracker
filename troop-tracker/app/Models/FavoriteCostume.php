<?php

namespace App\Models;

use App\Models\Base\FavoriteCostume as BaseFavoriteCostume;

class FavoriteCostume extends BaseFavoriteCostume
{
	protected $fillable = [
		'trooperid',
		'costumeid'
	];
}
