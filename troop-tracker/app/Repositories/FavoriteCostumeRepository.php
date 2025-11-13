<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\FavoriteCostume;
use App\Models\Trooper;
use Illuminate\Support\Collection;

/**
 * Repository for handling business logic related to a trooper's favorite costumes.
 */
class FavoriteCostumeRepository
{
    /**
     * Retrieves the favorite costumes for a given trooper.
     *
     * @param Trooper|int $trooper The Trooper model or the trooper's ID.
     * @param bool $include_costume Whether to eager-load the associated costume and its owning club.
     * @return Collection<int, FavoriteCostume> A collection of FavoriteCostume models.
     */
    public function getForTrooper(Trooper|int $trooper, bool $include_costume = false): Collection
    {
        if ($trooper instanceof Trooper)
        {
            $trooper = $trooper->id;
        }

        $query = FavoriteCostume::where('trooperid', $trooper);

        if ($include_costume)
        {
            return $query->with('costume.owningClub')
                ->get()
                ->sortBy(fn($fc) => $fc->costume->costume);
            ;
        }

        return $query->get();
    }

    /**
     * Adds a costume to a trooper's list of favorites.
     * If the favorite already exists, it does nothing.
     *
     * @param int $trooper_id The ID of the trooper.
     * @param int $costume_id The ID of the costume to add.
     */
    public function add(int $trooper_id, int $costume_id): void
    {
        FavoriteCostume::firstOrCreate([
            'trooperid' => $trooper_id,
            'costumeid' => $costume_id,
        ]);
    }

    /**
     * Removes a costume from a trooper's list of favorites.
     *
     * @param int $trooper_id The ID of the trooper.
     * @param int $costume_id The ID of the costume to remove.
     */
    public function remove(int $trooper_id, int $costume_id): void
    {
        FavoriteCostume::where('trooperid', $trooper_id)
            ->where('costumeid', $costume_id)
            ->delete();
    }
}