<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\MembershipStatus;
use App\Models\Squad;
use App\Models\Trooper;
use App\Models\TrooperClub;
use App\Models\TrooperSquad;

/**
 * Repository for managing Trooper-related operations,
 * particularly for retrieving Trooper data.
 */
class TrooperRepository
{
    /**
     * TrooperRepository constructor.
     * @param ClubRepository $clubs The repository for club-related operations.
     */
    public function __construct(private readonly ClubRepository $clubs)
    {
    }

    /**
     * Finds a Trooper by its primary key.
     *
     * @param int $id The primary key of the trooper.
     * @return Trooper|null The Trooper model if found, otherwise null.
     */
    public function getById(int $id): ?Trooper
    {
        return Trooper::find($id);
    }

    /**
     * Retrieves a Trooper by their forum username.
     *
     * @param string $username The forum username of the trooper.
     * @return Trooper|null The Trooper model if found, otherwise null.
     */
    public function getByForumUsername(string $username): ?Trooper
    {
        return Trooper::where(Trooper::USERNAME, $username)->first();
    }
}