<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Setting;

/**
 * Repository for handling data access operations related to Settings.
 */
class SettingRepository
{
    /**
     * Finds a Setting by its primary key.
     *
     * @return Setting|null The Setting model if found, otherwise null.
     */
    public function get(): ?Setting
    {
        return Setting::first();
    }
}