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
 * @property int $event_upload_id
 * @property int $trooper_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property EventUpload $event_upload
 * @property Trooper $trooper
 *
 * @package App\Models\Base
 */
class EventUploadTag extends Model
{
    const ID = 'id';
    const EVENT_UPLOAD_ID = 'event_upload_id';
    const TROOPER_ID = 'trooper_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_event_upload_tags';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_UPLOAD_ID => 'int',
        self::TROOPER_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function event_upload()
    {
        return $this->belongsTo(EventUpload::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }
}
