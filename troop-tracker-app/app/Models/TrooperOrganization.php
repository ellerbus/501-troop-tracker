<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperOrganization as BaseTrooperOrganization;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperOrganization extends BaseTrooperOrganization
{
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::TROOPER_ID,
        self::ORGANIZATION_ID,
        self::IDENTIFIER,
        self::NOTIFY,
        self::STATUS,
    ];
    protected function casts(): array
    {
        return array_merge($this->casts, [
            self::STATUS => MembershipStatus::class,
        ]);
    }
}
