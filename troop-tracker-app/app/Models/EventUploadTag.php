<?php

namespace App\Models;

use App\Models\Base\EventUploadTag as BaseEventUploadTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventUploadTag extends BaseEventUploadTag
{
    use HasFactory;

    protected $fillable = [
        self::EVENT_UPLOAD_ID,
        self::TROOPER_ID
    ];
}
