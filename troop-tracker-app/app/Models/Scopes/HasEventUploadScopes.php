<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\EventUploadTag;
use Illuminate\Database\Eloquent\Builder;

trait HasEventUploadScopes
{
    public function scopeByEvent(Builder $query, int $event_id): Builder
    {
        return $query->with('event_upload_tags')
            ->where(self::EVENT_ID, $event_id);
    }
    public function scopeByTrooper(Builder $query, int $trooper_id): Builder
    {
        return $query->with('event_upload_tags')
            ->whereHas('event_upload_tags', fn($q) => $q->where(EventUploadTag::TROOPER_ID, $trooper_id));
    }
}