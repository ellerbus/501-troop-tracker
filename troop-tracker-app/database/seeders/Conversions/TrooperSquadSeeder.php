<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipStatus;
use App\Models\TrooperSquad;
use Database\Seeders\Conversions\Traits\HasMappedEnums;
use Database\Seeders\Conversions\Traits\HasMappedSquads;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperSquadSeeder extends Seeder
{
    use HasMappedEnums;
    use HasMappedSquads;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $squad_map = $this->getSquadMap();

        $legacy_troopers = DB::table('troopers')
            ->join('tt_troopers', 'troopers.id', '=', 'tt_troopers.id')
            ->whereNotNull('troopers.email')
            ->select('troopers.*')
            ->get();

        foreach ($legacy_troopers as $trooper)
        {
            foreach ($squad_map as $key => $squad)
            {
                $notify = $trooper->{'esquad' . $key} == 1;

                $status = MembershipStatus::None;

                if ($trooper->squad == $key)
                {
                    $status = MembershipStatus::Member;
                }

                TrooperSquad::firstOrCreate([
                    TrooperSquad::TROOPER_ID => $trooper->id,
                    TrooperSquad::SQUAD_ID => $squad['id'],
                ], [
                    TrooperSquad::NOTIFY => $notify,
                    TrooperSquad::STATUS => $status,
                ]);
            }
        }
    }
}