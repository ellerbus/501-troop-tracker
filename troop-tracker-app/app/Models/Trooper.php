<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\TrooperPermissions;
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
    ];

    protected $hidden = [
        self::PASSWORD,
        self::REMEMBER_TOKEN,
    ];

    protected function casts()
    {
        return array_merge($this->casts, [
            self::PERMISSIONS => TrooperPermissions::class,
            self::EMAIL => LowerCast::class
        ]);
    }

    public function isUnapproved(): bool
    {
        return !$this->approved;
    }

    public function attachOrganization(int $organization_id, string $identifier, MembershipStatus $status): void
    {
        $this->organizations()->attach($organization_id, [
            TrooperOrganization::IDENTIFIER => $identifier,
            TrooperOrganization::STATUS => $status,
            TrooperOrganization::NOTIFY => true
        ]);
    }

    public function detachOrganization(int $organization_id, MembershipStatus $status = MembershipStatus::None): void
    {
        $this->trooper_costumes()
            ->where(TrooperCostume::COSTUME_ID, $organization_id)
            ->update([
                TrooperOrganization::STATUS => $status,
            ]);
    }


    public function attachCostume(int $organization_costume_id): void
    {
        $this->trooper_costumes()->create([
            TrooperCostume::COSTUME_ID => $organization_costume_id,
        ]);
    }

    public function detachCostume(int $organization_costume_id): void
    {
        $this->trooper_costumes()
            ->where(TrooperCostume::COSTUME_ID, $organization_costume_id)
            ->delete();
    }

    public function hasActiveOrganizationStatus(): bool
    {
        return $this->organizations()
            ->active()
            ->wherePivotNotIn(TrooperOrganization::STATUS, [MembershipStatus::None, MembershipStatus::Retired])
            ->exists();
    }

    public function assignedOrganizations(int $organization_id = null): Collection
    {
        $query = $this->organizations()->active()->orderBy(Organization::NAME);

        if ($organization_id)
        {
            $query->where('tt_organizations.' . Organization::ID, $organization_id);
        }

        return $query->get();
    }

    public function costumes(int $organization_id = null): Collection
    {
        return $this->trooper_costumes()
            ->with(['costume.organization']) // eager-load both costume and its organization
            ->get()
            ->map(fn($tc) => $tc->costume)
            ->filter(fn($cc) => $organization_id ? $cc->organization_id === $organization_id : true)
            ->values(); // reindex the collection
    }
}