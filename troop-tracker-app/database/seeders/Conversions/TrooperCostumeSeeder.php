<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipStatus;
use App\Models\TrooperCostume;
use Database\Seeders\Conversions\Traits\HasMappedEnums;
use Database\Seeders\Conversions\Traits\HasMappedSquads;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperCostumeSeeder extends Seeder
{
    use HasMappedEnums;
    use HasMappedSquads;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $squad_map = $this->getSquadMap();

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