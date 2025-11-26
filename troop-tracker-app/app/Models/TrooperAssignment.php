<?php

namespace App\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\TrooperAssignment as BaseTrooperAssignment;
use App\Models\Concerns\HasTrooperStamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperAssignment extends BaseTrooperAssignment
{
    use HasFactory;
    use HasTrooperStamps;
}
