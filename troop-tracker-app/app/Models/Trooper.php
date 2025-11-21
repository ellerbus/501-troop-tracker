<?php

namespace App\Models;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\Trooper as BaseTrooper;
use App\Models\Casts\LowerCast;
use App\Models\Concerns\HasObserver;
use App\Models\Scopes\HasTrooperScopes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class Trooper extends BaseTrooper implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    use HasFactory;
    use Notifiable;
    use HasTrooperScopes;
    use HasObserver;

    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PHONE,
        self::PASSWORD,
        self::MEMBERSHIP_STATUS,
        self::MEMBERSHIP_ROLE,
        self::INSTANT_NOTIFICATION,
        self::ATTENDANCE_NOTIFICATION,
        self::COMMAND_STAFF_NOTIFICATION,
    ];

    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected function casts()
    {
        return array_merge($this->casts, [
            self::MEMBERSHIP_STATUS => MembershipStatus::class,
            self::MEMBERSHIP_ROLE => MembershipRole::class,
            self::EMAIL => LowerCast::class
        ]);
    }

    public function isActive(): bool
    {
        return $this->membership_status == MembershipStatus::Active;
    }

    public function isDenied(): bool
    {
        return $this->membership_status == MembershipStatus::Denied;
    }

    public function attachCostume(int $costume_id): void
    {
        if (!$this->costumes()->where(TrooperCostume::COSTUME_ID, $costume_id)->exists())
        {
            $this->costumes()->attach($costume_id);
        }
    }

    public function detachCostume(int $costume_id): void
    {
        $this->costumes()->detach($costume_id);
    }

    public function hasActiveOrganizationStatus(): bool
    {
        return $this->organizations()
            ->active()
            ->wherePivotNotIn(TrooperOrganization::MEMBERSHIP_STATUS, [MembershipStatus::Pending, MembershipStatus::Retired])
            ->exists();
    }

    public function assignedOrganizations(?int $organization_id): Collection
    {
        $query = $this->organizations()
            ->active()
            ->wherePivot(TrooperOrganization::MEMBERSHIP_STATUS, MembershipStatus::Active);

        if ($organization_id)
        {
            $query->where('tt_organizations.' . Organization::ID, $organization_id);
        }

        return $query->get();
    }

    public function getFlatOrganizationList(): array
    {
        $flat = [];

        $organizations = $this->organizations()
            ->wherePivot(TrooperOrganization::MEMBERSHIP_STATUS, MembershipStatus::Active)
            ->get();

        foreach ($organizations as $organization)
        {
            $identifier = $organization->pivot->identifier ?? '(none)';

            // Try to find a region under this org
            $regions = $this->regions()
                ->where(Region::ORGANIZATION_ID, $organization->id)
                ->wherePivot(TrooperRegion::MEMBERSHIP_STATUS, MembershipStatus::Active)
                ->get();

            if ($regions->count() == 0)
            {
                // No region → just org
                $flat[$identifier] = $organization->name;
                continue;
            }

            foreach ($regions as $region)
            {
                // Try to find a unit under this region
                $units = $this->units()
                    ->where(Unit::REGION_ID, $region->id)
                    ->wherePivot(TrooperUnit::MEMBERSHIP_STATUS, MembershipStatus::Active)
                    ->get();

                if ($units->count() == 0)
                {
                    // Org + Region only
                    $flat[$identifier] = "{$organization->name}, {$region->name}";
                    continue;
                }

                foreach ($units as $unit)
                {
                    // Org + Region + Unit
                    $flat[$identifier] = "{$organization->name}, {$region->name}, {$unit->name}";
                }
            }
        }

        return $flat;
    }

    // public function costumes(int $organization_id = null): Collection
    // {
    //     return $this->trooper_costumes()
    //         ->with(['costume.organization']) // eager-load both costume and its organization
    //         ->get()
    //         ->map(fn($tc) => $tc->costume)
    //         ->filter(fn($cc) => $organization_id ? $cc->organization_id === $organization_id : true)
    //         ->values(); // reindex the collection
    // }
}