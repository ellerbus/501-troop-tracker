<?php

namespace App\Models;

use App\Models\Base\Upload as BaseUpload;

class Upload extends BaseUpload
{
	protected $fillable = [
		self::TROOPID,
		self::TROOPERID,
		self::FILENAME,
		self::ADMIN,
		self::DATE
	];
}
