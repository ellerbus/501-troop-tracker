<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\TrooperClub;
use Database\Seeders\Conversions\Traits\HasClubMaps;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperClubSeeder extends Seeder
{
    use HasEnumMaps;
    use HasClubMaps;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club_map = $this->getClubMap();

        $legacy_troopers = DB::table('troopers')
            ->join('tt_troopers', 'troopers.id', '=', 'tt_troopers.id')
            ->whereNotNull('troopers.email')
            ->select('troopers.*')
            ->get();

        foreach ($legacy_troopers as $trooper)
        {
            foreach ($club_map as $permissions => $club)
            {
                $value = $club['value'];

                $identifier = '';

                if ($club['identity'] != '')
                {
                    $identifier = $trooper->{$club['identity']};
                }

                $notify = $trooper->{'esquad' . $value} == 1;
                $status = $this->mapLegacyStatus($trooper->{$permissions});

                $t = TrooperClub::where(TrooperClub::TROOPER_ID, $trooper->id)
                    ->where(TrooperClub::CLUB_ID, $club['id'])
                    ->first();

                if ($t == null)
                {
                    $t = new TrooperClub();

                    $t->trooper_id = $trooper->id;
                    $t->club_id = $club['id'];
                }

                $t->identifier = $identifier;
                $t->notify = $notify;
                $t->status = $status;

                $t->save();
            }
        }
    }
}