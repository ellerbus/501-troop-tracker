<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Club;
use Illuminate\Support\Collection;

/**
 * Repository for handling data access operations related to Clubs.
 */
class ClubRepository
{
    /**
     * Finds a Club by its primary key.
     *
     * @param int $id The primary key of the club.
     * @return Club|null The Club model if found, otherwise null.
     */
    public function getById(int $id): ?Club
    {
        return Club::find($id);
    }

    /**
     * Retrieves all active clubs, ordered by name.
     *
     * The result is cached for the duration of a single request using the `once()` helper
     * to prevent redundant database queries.
     *
     * @param bool $include_squads If true, the 'squads' relationship will be eager-loaded.
     * @param bool $include_sinclude_costume If true, the 'costumes' relationship will be eager-loaded.
     * @return Collection<int, Club>
     */
    public function findAllActive(bool $include_squads = false, bool $include_costumes = false): Collection
    {
        return once(fn() => $this->queryActiveClubs(
            include_squads: $include_squads,
            include_costumes: $include_costumes
        ));
    }

    private function queryActiveClubs(bool $include_squads, bool $include_costumes): Collection
    {
        $query = Club::where('active', true)->orderBy('name');

        if ($include_squads)
        {
            $query->with('squads');
        }

        if ($include_costumes)
        {
            $query->with('costumes');
        }

        return $query->get();
    }
}