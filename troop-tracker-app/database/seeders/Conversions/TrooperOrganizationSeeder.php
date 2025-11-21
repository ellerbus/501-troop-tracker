<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipRole;
use App\Models\TrooperOrganization;
use Database\Seeders\Conversions\Traits\HasClubMaps;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperOrganizationSeeder extends Seeder
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

                if ($identifier == null || $identifier == '')
                {
                    continue;
                }

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

                $t = TrooperOrganization::where(TrooperOrganization::TROOPER_ID, $trooper->id)
                    ->where(TrooperOrganization::ORGANIZATION_ID, $club['id'])
                    ->first();

                if ($t == null)
                {
                    $t = new TrooperOrganization();

                    $t->trooper_id = $trooper->id;
                    $t->organization_id = $club['id'];
                }

                $t->identifier = $identifier;
                $t->notify = $notify;
                $t->membership_status = $membership_status;
                $t->membership_role = $membership_role;

                $t->save();
            }
        }
    }
}