<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\TrooperCostume;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperCostumeSeeder extends Seeder
{
    use HasEnumMaps;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favorites = DB::table('favorite_costumes')
            ->join('tt_troopers', 'favorite_costumes.trooperid', '=', 'tt_troopers.id')
            ->join('tt_club_costumes', 'favorite_costumes.costumeid', '=', 'tt_club_costumes.id')
            ->select('favorite_costumes.*')
            ->get();

        foreach ($favorites as $favorite)
        {
            TrooperCostume::firstOrCreate([
                TrooperCostume::TROOPER_ID => $favorite->trooperid,
                TrooperCostume::COSTUME_ID => $favorite->costumeid,
            ], []);
        }
    }
}