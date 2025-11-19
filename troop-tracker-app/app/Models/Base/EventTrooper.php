<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\ClubCostume;
use App\Models\Event;
use App\Models\Trooper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventTrooper
 * 
 * @property int $id
 * @property int $event_id
 * @property int $trooper_id
 * @property int|null $club_costume_id
 * @property int|null $backup_club_costume_id
 * @property int|null $added_by_trooper_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Trooper $trooper
 * @property ClubCostume|null $club_costume
 * @property Event $event
 *
 * @package App\Models\Base
 */
class EventTrooper extends Model
{
    const ID = 'id';
    const EVENT_ID = 'event_id';
    const TROOPER_ID = 'trooper_id';
    const CLUB_COSTUME_ID = 'club_costume_id';
    const BACKUP_CLUB_COSTUME_ID = 'backup_club_costume_id';
    const ADDED_BY_TROOPER_ID = 'added_by_trooper_id';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_event_troopers';

    protected $casts = [
        self::ID => 'int',
        self::EVENT_ID => 'int',
        self::TROOPER_ID => 'int',
        self::CLUB_COSTUME_ID => 'int',
        self::BACKUP_CLUB_COSTUME_ID => 'int',
        self::ADDED_BY_TROOPER_ID => 'int',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function trooper()
    {
        return $this->belongsTo(Trooper::class);
    }

    public function club_costume()
    {
        return $this->belongsTo(ClubCostume::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
