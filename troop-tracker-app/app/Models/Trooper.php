<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use App\Enums\Permissions;
use App\Models\Base\Trooper as BaseTrooper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class Trooper extends BaseTrooper implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    use HasFactory;
    use Notifiable;

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

    public function hasActiveClubStatus(): bool
    {
        return $this->clubs()
            ->wherePivotNotIn('status', [MembershipStatus::None, MembershipStatus::Retired])
            ->where('tt_clubs.active', true)
            ->exists();
    }
}