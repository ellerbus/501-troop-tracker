<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\TrooperUnit;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Database\Seeders\Conversions\Traits\HasSquadMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperUnitSeeder extends Seeder
{
    use HasEnumMaps;
    use HasSquadMaps;

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

                $membership_status = MembershipStatus::Pending;
                $membership_role = MembershipRole::Member;

                if ($trooper->squad == $key)
                {
                    $membership_status = MembershipStatus::Active;
                }

                $t = TrooperUnit::where(TrooperUnit::TROOPER_ID, $trooper->id)
                    ->where(TrooperUnit::UNIT_ID, $squad['id'])
                    ->first();

                if ($t == null)
                {
                    $t = new TrooperUnit();

                    $t->trooper_id = $trooper->id;
                    $t->unit_id = $squad['id'];
                }

                $t->notify = $notify;
                $t->membership_status = $membership_status;
                $t->membership_role = $membership_role;

                $t->save();
            }
        }
    }
}