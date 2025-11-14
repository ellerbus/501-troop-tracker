<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\ClubCostume;
use Database\Seeders\Conversions\Traits\HasClubMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubCostumeSeeder extends Seeder
{
    use HasClubMaps;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club_map = collect($this->getClubMap());

        $legacy_costumes = DB::table('costumes')->get();

        foreach ($legacy_costumes as $costume)
        {
            $club = $club_map->firstWhere('value', $costume->club) ?? null;

            if (is_null($club))
            {
                continue;
            }

            ClubCostume::firstOrCreate([
                ClubCostume::NAME => $costume->costume,
                ClubCostume::CLUB_ID => $club['id'],
            ], [
                ClubCostume::ID => $costume->id,
            ]);
        }
    }
}