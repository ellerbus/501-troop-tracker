<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Award;
use App\Models\Costume;
use App\Models\Event;
use App\Models\EventTrooper;
use App\Models\EventUpload;
use App\Models\EventUploadTag;
use App\Models\Organization;
use App\Models\Region;
use App\Models\TrooperAchievement;
use App\Models\TrooperAward;
use App\Models\TrooperCostume;
use App\Models\TrooperDonation;
use App\Models\TrooperOrganization;
use App\Models\TrooperRegion;
use App\Models\TrooperUnit;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Trooper
 * 
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $username
 * @property string $password
 * @property Carbon|null $last_active_at
 * @property bool $approved
 * @property string $membership_status
 * @property string $membership_role
 * @property bool $instant_notification
 * @property bool $attendance_notification
 * @property bool $command_staff_notification
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Event[] $events
 * @property Collection|EventUploadTag[] $event_upload_tags
 * @property Collection|EventUpload[] $event_uploads
 * @property TrooperAchievement|null $trooper_achievement
 * @property Collection|Award[] $awards
 * @property Collection|Costume[] $costumes
 * @property Collection|TrooperDonation[] $trooper_donations
 * @property Collection|Organization[] $organizations
 * @property Collection|Region[] $regions
 * @property Collection|Unit[] $units
 *
 * @package App\Models\Base
 */
class Trooper extends Model
{
    const ID = 'id';
    const NAME = 'name';
    const PHONE = 'phone';
    const EMAIL = 'email';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const LAST_ACTIVE_AT = 'last_active_at';
    const APPROVED = 'approved';
    const MEMBERSHIP_STATUS = 'membership_status';
    const MEMBERSHIP_ROLE = 'membership_role';
    const INSTANT_NOTIFICATION = 'instant_notification';
    const ATTENDANCE_NOTIFICATION = 'attendance_notification';
    const COMMAND_STAFF_NOTIFICATION = 'command_staff_notification';
    const REMEMBER_TOKEN = 'remember_token';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'tt_troopers';

    protected $casts = [
        self::ID => 'int',
        self::EMAIL_VERIFIED_AT => 'datetime',
        self::LAST_ACTIVE_AT => 'datetime',
        self::APPROVED => 'bool',
        self::INSTANT_NOTIFICATION => 'bool',
        self::ATTENDANCE_NOTIFICATION => 'bool',
        self::COMMAND_STAFF_NOTIFICATION => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'tt_event_troopers')
                    ->withPivot(EventTrooper::ID, EventTrooper::COSTUME_ID, EventTrooper::BACKUP_COSTUME_ID, EventTrooper::ADDED_BY_TROOPER_ID, EventTrooper::STATUS, EventTrooper::CREATED_ID, EventTrooper::UPDATED_ID)
                    ->withTimestamps();
    }

    public function event_upload_tags()
    {
        return $this->hasMany(EventUploadTag::class);
    }

    public function event_uploads()
    {
        return $this->hasMany(EventUpload::class);
    }

    public function trooper_achievement()
    {
        return $this->hasOne(TrooperAchievement::class);
    }

    public function awards()
    {
        return $this->belongsToMany(Award::class, 'tt_trooper_awards')
                    ->withPivot(TrooperAward::ID, TrooperAward::CREATED_ID, TrooperAward::UPDATED_ID)
                    ->withTimestamps();
    }

    public function costumes()
    {
        return $this->belongsToMany(Costume::class, 'tt_trooper_costumes')
                    ->withPivot(TrooperCostume::ID, TrooperCostume::CREATED_ID, TrooperCostume::UPDATED_ID)
                    ->withTimestamps();
    }

    public function trooper_donations()
    {
        return $this->hasMany(TrooperDonation::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'tt_trooper_organizations')
                    ->withPivot(TrooperOrganization::ID, TrooperOrganization::IDENTIFIER, TrooperOrganization::NOTIFY, TrooperOrganization::MEMBERSHIP_STATUS, TrooperOrganization::MEMBERSHIP_ROLE, TrooperOrganization::CREATED_ID, TrooperOrganization::UPDATED_ID)
                    ->withTimestamps();
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'tt_trooper_regions')
                    ->withPivot(TrooperRegion::ID, TrooperRegion::NOTIFY, TrooperRegion::MEMBERSHIP_STATUS, TrooperRegion::MEMBERSHIP_ROLE, TrooperRegion::CREATED_ID, TrooperRegion::UPDATED_ID)
                    ->withTimestamps();
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'tt_trooper_units')
                    ->withPivot(TrooperUnit::ID, TrooperUnit::NOTIFY, TrooperUnit::MEMBERSHIP_STATUS, TrooperUnit::MEMBERSHIP_ROLE, TrooperUnit::CREATED_ID, TrooperUnit::UPDATED_ID)
                    ->withTimestamps();
    }
}
