<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\Permissions;
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
 * @property Permissions $permissions
 * @property int $spTrooper
 * @property int $spCostume
 * @property int $spAward
 * @property MembershipStatus $p501
 * @property MembershipStatus $pRebel
 * @property MembershipStatus $pDroid
 * @property MembershipStatus $pMando
 * @property MembershipStatus $pOther
 * @property MembershipStatus|null $pSG
 * @property MembershipStatus|null $pDE
 * @property string $tkid
 * @property string $forum_id
 * @property string|null $rebelforum
 * @property int|null $mandoid
 * @property string $sgid
 * @property int $de_id
 * @property string|null $password
 * @property Carbon $last_active
 * @property bool $approved
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
        'permissions' => Permissions::class,
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
        'esquad6' => 'bool',
        'esquad7' => 'bool',
        'esquad8' => 'bool',
        'esquad9' => 'bool',
        'esquad10' => 'bool',
        'esquad13' => 'bool',
        'efast' => 'bool',
        'ecommandnotify' => 'bool',
        'econfirm' => 'bool',
        'datecreated' => 'datetime'
    ];

    public function favoriteCostumes()
    {
        return $this->hasMany(FavoriteCostume::class, 'trooperid', 'id');
    }

    public function trooperClubs()
    {
        return $this->hasMany(TrooperClub::class, 'trooper_id', 'id');
    }

    public function trooperSquads()
    {
        return $this->hasMany(TrooperSquad::class, 'trooper_id', 'id');
    }

    public function isUnapproved(): bool
    {
        return $this->approved == false;
    }

    public function getAassignedClubIds(): array
    {
        //  TODO replace with child table not hard coded map
        $ids = [];

        $properties = [
            'p501' => 0,
            'pRebel' => 6,
            'pDroid' => 7,
            'pMando' => 8,
            'pOther' => 9,
            'pSG' => 10,
            'pDE' => 13,
        ];

        foreach ($properties as $key => $id)
        {
            $value = $this->{$key};

            if (!is_null($value) && $value != MembershipStatus::None)
            {
                $ids[] = $id;
            }
        }

        foreach ($this->trooperClubs as $club)
        {
            if (!in_array($club->club_id, $ids))
            {
                $ids[] = $club->club_id;
            }
        }
        return $ids;

    }
}


