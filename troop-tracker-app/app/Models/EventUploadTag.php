<?php

namespace App\Models;

use App\Models\Base\EventUploadTag as BaseEventUploadTag;

class EventUploadTag extends BaseEventUploadTag
{
	protected $fillable = [
		self::UPLOAD_ID,
		self::TROOPER_ID
	];
}
