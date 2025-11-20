<?php

namespace App\Models;

use App\Models\Base\TrooperDonation as BaseTrooperDonation;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasTrooperDonationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperDonation extends BaseTrooperDonation
{
    use HasTrooperDonationScopes;
    use HasFactory;
    use HasTrooperStamps;

    protected $fillable = [
        self::TROOPER_ID,
        self::AMOUNT,
        self::TXN_ID,
        self::TXN_TYPE
    ];
}
