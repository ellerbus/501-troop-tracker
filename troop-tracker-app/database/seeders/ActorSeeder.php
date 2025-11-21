<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organization;
use App\Models\Trooper;
use App\Models\TrooperOrganization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ActorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $actor = Trooper::find(501) ?? new Trooper(['id' => 501]);

        $actor->name = 'Sith Lord';
        $actor->email = 'sith@galaxy-far-far-away.com';
        $actor->username = 'sith';
        $actor->password = Hash::make('password');
        $actor->membership_status = MembershipStatus::Active;
        $actor->membership_role = MembershipRole::Admin;

        $actor->save();

        if ($actor->organizations->count() == 0)
        {
            $organization = Organization::firstWhere(Organization::SLUG, '501st');

            $actor->organizations()->save($organization, [
                TrooperOrganization::IDENTIFIER => '99999',
                TrooperOrganization::MEMBERSHIP_STATUS => MembershipStatus::Active,
                TrooperOrganization::MEMBERSHIP_ROLE => MembershipRole::Member,
                TrooperOrganization::NOTIFY => true
            ]);
        }
    }
}