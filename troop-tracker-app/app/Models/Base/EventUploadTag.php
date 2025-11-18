<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\EventUpload;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventUploadTag
 * 
 * @property int $id
 * @property int $upload_id
 * @property int $trooper_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Trooper $trooper
 * @property EventUpload $event_upload
 *
 * @package App\Models\Base
 */
class EventUploadTag extends Model
{
    const ID = 'id';
    const UPLOAD_ID = 'upload_id';
    const TROOPER_ID = 'trooper_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_event_upload_tags';

    protected $casts = [
        self::ID => 'int',
        self::UPLOAD_ID => 'int',
        self::TROOPER_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }

    public function event_upload()
    {
        return $this->belongsTo(EventUpload::class, \App\Models\EventUploadTag::UPLOAD_ID);
    }
}
