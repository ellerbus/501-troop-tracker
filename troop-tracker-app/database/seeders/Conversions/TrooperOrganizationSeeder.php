<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Base\Organization;
use App\Models\TrooperAssignment;
use App\Models\TrooperOrganization;
use Database\Seeders\Conversions\Traits\HasClubMaps;
use Database\Seeders\Conversions\Traits\HasEnumMaps;
use Database\Seeders\Conversions\Traits\HasSquadMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperOrganizationSeeder extends Seeder
{
    use HasEnumMaps;
    use HasClubMaps;
    use HasSquadMaps;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacy_troopers = DB::table('troopers')->get();

        $this->loadOrganizations($legacy_troopers);
        $this->loadRegions($legacy_troopers);
        $this->loadUnits($legacy_troopers);
    }

    private function loadOrganizations($legacy_troopers)
    {
        $club_map = $this->getClubMap();

        foreach ($legacy_troopers as $trooper)
        {
            foreach ($club_map as $permissions => $club)
            {
                $value = $club['value'];

                if (!isset($club['id']))
                {
                    continue;
                }

                $organization = Organization::findOrFail($club['id']);

                $identifier = '';

                if ($club['identity'] != '')
                {
                    $identifier = $trooper->{$club['identity']};
                }

                if ($identifier != null && $identifier != '' && $identifier != '0')
                {
                    $to = TrooperOrganization::where(TrooperOrganization::TROOPER_ID, $trooper->id)
                        ->where(TrooperOrganization::ORGANIZATION_ID, $organization->id)
                        ->first();

                    if ($to == null)
                    {
                        $to = new TrooperOrganization();

                        $to->trooper_id = $trooper->id;
                        $to->organization_id = $organization->id;
                        $to->identifier = $identifier;

                        $to->save();
                    }
                }

                $notify = $trooper->{'esquad' . $value} == 1;
                $membership_status = $this->mapLegacyStatus($trooper->{$permissions});
                $membership_role = $this->getMembershipRole($trooper);

                if ($membership_status == 'none' && !$notify)
                {
                    //  no status and not getting notified, skip
                    continue;
                }

                $ta = $this->getOrganization($trooper, $organization);

                $ta->notify = $notify;
                $ta->membership_status = $membership_status;
                $ta->membership_role = $membership_role;

                $ta->save();
            }
        }
    }

    private function loadRegions($legacy_troopers)
    {
        $club_map = $this->getClubMap();

        foreach ($legacy_troopers as $trooper)
        {
            foreach ($club_map as $permissions => $club)
            {
                $value = $club['value'];

                $notify = $trooper->{'esquad' . $value} == 1;
                $membership_status = $this->mapLegacyStatus($trooper->{$permissions});
                $membership_role = $this->getMembershipRole($trooper);

                if ($membership_status == 'none' && !$notify)
                {
                    continue;
                }

                if (!isset($club['id']))
                {
                    continue;
                }

                $organization = once(fn() => Organization::find($club['id']));

                //  right now only one region seeded per organization
                $region = $organization->organizations()->first();

                $ta = $this->getOrganization($trooper, $region);

                $ta->notify = $notify;
                $ta->membership_status = $membership_status;
                $ta->membership_role = $membership_role;

                $ta->save();
            }
        }
    }

    private function loadUnits($legacy_troopers)
    {
        $squad_map = $this->getSquadMap();

        foreach ($legacy_troopers as $trooper)
        {
            foreach ($squad_map as $key => $squad)
            {
                $notify = $trooper->{'esquad' . $key} == 1;

                $membership_status = MembershipStatus::None;
                $membership_role = $this->getMembershipRole($trooper);

                if ($trooper->squad == $key)
                {
                    $membership_status = MembershipStatus::Active;
                }

                $unit = once(fn() => Organization::find($squad['id']));

                $ta = $this->getOrganization($trooper, $unit);

                $ta->notify = $notify;
                $ta->membership_status = $membership_status;
                $ta->membership_role = $membership_role;

                $ta->save();
            }
        }
    }

    private function getOrganization($trooper, Organization $organization): TrooperAssignment
    {
        $ta = TrooperAssignment::where(TrooperAssignment::TROOPER_ID, $trooper->id)
            ->where(TrooperAssignment::ORGANIZATION_ID, $organization->id)
            ->first();

        if ($ta == null)
        {
            $ta = new TrooperAssignment();

            $ta->trooper_id = $trooper->id;
            $ta->organization_id = $organization->id;
        }

        return $ta;
    }

    private function getMembershipRole($trooper): MembershipRole
    {
        return $trooper->permissions == 4 ? MembershipRole::Handler : MembershipRole::Member;
    }
}