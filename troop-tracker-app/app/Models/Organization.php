<?php

namespace App\Models;

use App\Models\Base\Organization as BaseOrganization;
use App\Models\Concerns\HasObserver;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasOrganizationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends BaseOrganization
{
    use HasObserver;
    use HasOrganizationScopes;
    use HasFactory;
    use HasTrooperStamps;

    /**
     * Alias for organization()
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Organization, Organization>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, self::PARENT_ID);
    }

    public function event_troopers()
    {
        return $this->hasManyThrough(EventTrooper::class, Costume::class);
    }

}
