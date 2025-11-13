<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperSquad as BaseTrooperSquad;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperSquad extends BaseTrooperSquad
{
    use HasFactory;

    protected $fillable = [
        'trooper_id',
        'squad_id',
        'notify',
        'membership_status'
    ];

    protected $casts = [
        'notify' => 'bool',
        'membership_status' => MembershipStatus::class
    ];
}
