<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipRole;
use App\Models\Base\Organization;
use App\Models\TrooperRegion;
use Database\Seeders\Conversions\Traits\HasClubMaps;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperRegionSeeder extends Seeder
{
    use HasClubMaps;
    use HasEnumMaps;

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

                $notify = $trooper->{'esquad' . $value} == 1;
                $membership_status = $this->mapLegacyStatus($trooper->{$permissions});
                $membership_role = $trooper->{$permissions} == 4 ? MembershipRole::Handler : MembershipRole::Member;

                if ($membership_status == 'none')
                {
                    continue;
                }

                if (!isset($club['id']))
                {
                    continue;
                }

                $organization = once(fn() => Organization::find($club['id']));

                $region = $organization->regions->first();

                $t = TrooperRegion::where(TrooperRegion::TROOPER_ID, $trooper->id)
                    ->where(TrooperRegion::REGION_ID, $region->id)
                    ->first();

                if ($t == null)
                {
                    $t = new TrooperRegion();

                    $t->trooper_id = $trooper->id;
                    $t->region_id = $region->id;
                }

                $t->notify = $notify;
                $t->membership_status = $membership_status;
                $t->membership_role = $membership_role;

                $t->save();
            }
        }
    }
}