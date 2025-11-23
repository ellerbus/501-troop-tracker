<?php

namespace App\Models;

use App\Models\Base\TrooperOrganization as BaseTrooperOrganization;
use App\Models\Scopes\HasTrooperOrganizationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrooperOrganization extends BaseTrooperOrganization
{
    use HasFactory;
    use HasTrooperOrganizationScopes;
}
