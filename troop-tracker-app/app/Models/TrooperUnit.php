<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Models\Base\TrooperUnit as BaseTrooperUnit;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperUnit extends BaseTrooperUnit
{
    use HasTrooperStamps;
    use HasFactory;

    protected $fillable = [
        self::TROOPER_ID,
        self::UNIT_ID,
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
