<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Event;
use App\Models\EventUploadTag;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventUpload
 * 
 * @property int $id
 * @property int $event_id
 * @property int $trooper_id
 * @property string $filename
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_id
 * @property int|null $updated_id
 * 
 * @property Event $event
 * @property Trooper $trooper
 * @property Collection|EventUploadTag[] $event_upload_tags
 *
 * @package App\Models\Base
 */
class EventUpload extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const TROOPER_ID = 'trooper_id';
    const FILENAME = 'filename';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const CREATED_ID = 'created_id';
    const UPDATED_ID = 'updated_id';
    protected $table = 'tt_event_uploads';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::TROOPER_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::CREATED_ID => 'int',
        self::UPDATED_ID => 'int'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }

    public function event_upload_tags()
    {
        return $this->hasMany(EventUploadTag::class);
    }
}
