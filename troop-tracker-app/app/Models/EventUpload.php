<?php

namespace App\Models;

use App\Models\Base\EventUpload as BaseEventUpload;
use App\Models\Scopes\HasEventUploadScopes;

class EventUpload extends BaseEventUpload
{
    use HasEventUploadScopes;

    protected $fillable = [
        self::EVENT_ID,
        self::TROOPER_ID,
        self::FILENAME
    ];
}
