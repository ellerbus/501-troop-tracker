<?php

namespace App\Models;

use App\Models\Base\Upload as BaseUpload;

class Upload extends BaseUpload
{
	protected $fillable = [
		'troopid',
		'trooperid',
		'filename',
		'admin',
		'date'
	];
}
