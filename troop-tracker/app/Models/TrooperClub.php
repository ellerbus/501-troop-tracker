<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperClub as BaseTrooperClub;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperClub extends BaseTrooperClub
{
    use HasFactory;

    protected $fillable = [
        'trooper_id',
        'club_id',
        'identifier',
        'notify',
        'membership_status'
    ];

    protected $casts = [
        'notify' => 'bool',
        'membership_status' => MembershipStatus::class
    ];
}
