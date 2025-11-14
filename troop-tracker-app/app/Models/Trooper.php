<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\Permissions;
use App\Models\Base\Trooper as BaseTrooper;
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
            self::PERMISSIONS => Permissions::class,
        ]);
    }

    public function isUnapproved(): bool
    {
        return !$this->approved;
    }

    public function attachCostume(int $costume_id): void
    {
        $this->trooper_costumes()->create([
            TrooperCostume::COSTUME_ID => $costume_id,
        ]);
    }

    public function detachCostume(int $costume_id): void
    {
        $this->trooper_costumes()
            ->where(TrooperCostume::COSTUME_ID, $costume_id)
            ->delete();
    }

    public function hasActiveClubStatus(): bool
    {
        return $this->clubs()
            ->active()
            ->wherePivotNotIn(TrooperClub::STATUS, [MembershipStatus::None, MembershipStatus::Retired])
            ->exists();
    }

    public function assignedClubs(int $club_id = null): Collection
    {
        $query = $this->clubs()->active()->orderBy(Club::NAME);

        if ($club_id)
        {
            $query->where('tt_clubs.' . Club::ID, $club_id);
        }

        return $query->get();
    }

    public function costumes(int $club_id = null): Collection
    {
        return $this->trooper_costumes()
            ->with(['club_costume.club']) // eager-load both costume and its club
            ->get()
            ->map(fn($tc) => $tc->club_costume)
            ->filter(fn($cc) => $club_id ? $cc->club_id === $club_id : true)
            ->values(); // reindex the collection
    }
}