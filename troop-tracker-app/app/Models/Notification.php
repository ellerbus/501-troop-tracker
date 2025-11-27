<?php

namespace App\Models;

use App\Enums\NotificationType;
use App\Models\Base\Notification as BaseNotification;
use App\Models\Concerns\HasTrooperStamps;
use App\Models\Scopes\HasNotificationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends BaseNotification
{
    use HasNotificationScopes;
    use HasFactory;
    use HasTrooperStamps;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {
        return array_merge($this->casts, [
            self::TYPE => NotificationType::class,
        ]);
    }
}
