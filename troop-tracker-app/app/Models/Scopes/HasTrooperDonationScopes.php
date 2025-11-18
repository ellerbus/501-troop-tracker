<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasTrooperDonationScopes
{
    public function scopeByTrooper(Builder $query, int $trooper_id): Builder
    {
        return $query->where(self::TROOPER_ID, $trooper_id)
            ->orderBy(self::CREATED_AT, "desc");
    }
}