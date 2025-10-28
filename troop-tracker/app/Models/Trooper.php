<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Trooper
 * 
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property int $squad
 * @property int $permissions
 * @property int $spTrooper
 * @property int $spCostume
 * @property int $spAward
 * @property int $p501
 * @property int $pRebel
 * @property int $pDroid
 * @property int $pMando
 * @property int $pOther
 * @property int|null $pSG
 * @property int|null $pDE
 * @property string $tkid
 * @property string $forum_id
 * @property string|null $rebelforum
 * @property int|null $mandoid
 * @property string $sgid
 * @property int $de_id
 * @property string|null $password
 * @property Carbon $last_active
 * @property int $approved
 * @property int $subscribe
 * @property int $theme
 * @property int $supporter
 * @property int $esquad0
 * @property bool|null $esquad1
 * @property bool|null $esquad2
 * @property bool|null $esquad3
 * @property bool|null $esquad4
 * @property bool|null $esquad5
 * @property int $esquad6
 * @property int $esquad7
 * @property int $esquad8
 * @property int $esquad9
 * @property int $esquad10
 * @property int $esquad13
 * @property bool|null $efast
 * @property bool|null $ecommandnotify
 * @property bool|null $econfirm
 * @property string|null $note
 * @property Carbon $datecreated
 *
 * @package App\Models
 */
class Trooper extends Authenticatable
{
    use HasFactory;

    protected $table = 'troopers';

    public $timestamps = false;

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'squad',
        'permissions',
        'spTrooper',
        'spCostume',
        'spAward',
        'p501',
        'pRebel',
        'pDroid',
        'pMando',
        'pOther',
        'pSG',
        'pDE',
        'tkid',
        'forum_id',
        'rebelforum',
        'mandoid',
        'sgid',
        'de_id',
        'password',
        'last_active',
        'approved',
        'subscribe',
        'theme',
        'supporter',
        'esquad0',
        'esquad1',
        'esquad2',
        'esquad3',
        'esquad4',
        'esquad5',
        'esquad6',
        'esquad7',
        'esquad8',
        'esquad9',
        'esquad10',
        'esquad13',
        'efast',
        'ecommandnotify',
        'econfirm',
        'note',
        'datecreated'
    ];

    protected $casts = [
        'user_id' => 'int',
        'squad' => 'int',
        'permissions' => 'int',
        'spTrooper' => 'int',
        'spCostume' => 'int',
        'spAward' => 'int',
        'p501' => MembershipStatus::class,
        'pRebel' => MembershipStatus::class,
        'pDroid' => MembershipStatus::class,
        'pMando' => MembershipStatus::class,
        'pOther' => MembershipStatus::class,
        'pSG' => MembershipStatus::class,
        'pDE' => MembershipStatus::class,
        'mandoid' => 'int',
        'de_id' => 'int',
        'last_active' => 'datetime',
        'approved' => 'bool',
        'subscribe' => 'int',
        'theme' => 'int',
        'supporter' => 'int',
        'esquad0' => 'int',
        'esquad1' => 'bool',
        'esquad2' => 'bool',
        'esquad3' => 'bool',
        'esquad4' => 'bool',
        'esquad5' => 'bool',
        'esquad6' => 'int',
        'esquad7' => 'int',
        'esquad8' => 'int',
        'esquad9' => 'int',
        'esquad10' => 'int',
        'esquad13' => 'int',
        'efast' => 'bool',
        'ecommandnotify' => 'bool',
        'econfirm' => 'bool',
        'datecreated' => 'datetime'
    ];

    public function isUnapproved(): bool
    {
        return $this->approved == false;
    }
}


