<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Club;
use App\Models\Donation;
use App\Models\Squad;
use App\Models\TrooperClub;
use App\Models\TrooperCostume;
use App\Models\TrooperSquad;
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
 * @property string $permissions
 * @property string $approved
 * @property bool $instant_notification
 * @property bool $attendance_notification
 * @property bool $command_staff_notification
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Donation[] $donations
 * @property Collection|Club[] $clubs
 * @property Collection|TrooperCostume[] $trooper_costumes
 * @property Collection|Squad[] $squads
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
    const PERMISSIONS = 'permissions';
    const APPROVED = 'approved';
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
        self::INSTANT_NOTIFICATION => 'bool',
        self::ATTENDANCE_NOTIFICATION => 'bool',
        self::COMMAND_STAFF_NOTIFICATION => 'bool',
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime'
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'tt_trooper_clubs')
                    ->withPivot(TrooperClub::ID, TrooperClub::IDENTIFIER, TrooperClub::NOTIFY, TrooperClub::STATUS)
                    ->withTimestamps();
    }

    public function trooper_costumes()
    {
        return $this->hasMany(TrooperCostume::class);
    }

    public function squads()
    {
        return $this->belongsToMany(Squad::class, 'tt_trooper_squads')
                    ->withPivot(TrooperSquad::ID, TrooperSquad::NOTIFY, TrooperSquad::STATUS)
                    ->withTimestamps();
    }
}
