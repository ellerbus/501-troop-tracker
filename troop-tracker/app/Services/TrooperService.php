<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trooper;

/**
 * Service for managing Trooper-related operations,
 * particularly for retrieving Trooper data.
 */
class TrooperService
{
    /**
     * Retrieves a Trooper by their forum username.
     *
     * @param string $username The forum username of the trooper.
     * @return Trooper|null The Trooper model if found, otherwise null.
     */
    public function getByForumUsername(string $username): ?Trooper
    {
        return Trooper::where('forum_id', $username)->first();
    }
}