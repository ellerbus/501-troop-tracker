<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Enums\TrooperPermissions;
use App\Models\Organization;
use App\Models\Trooper;
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
        $actor->permissions = TrooperPermissions::Admin;
        $actor->approved = true;

        $actor->save();

        if ($actor->organizations->count() == 0)
        {
            $organization = Organization::firstWhere(Organization::SLUG, '501st');

            $actor->attachOrganization($organization->id, '99999', MembershipStatus::Member);
        }
    }
}