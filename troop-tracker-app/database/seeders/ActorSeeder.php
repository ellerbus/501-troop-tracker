<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Enums\TrooperPermissions;
use App\Models\Club;
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
        $actor->email = 'sith@galaxy-far-away.com';
        $actor->username = 'sith';
        $actor->password = Hash::make('password');
        $actor->permissions = TrooperPermissions::Admin;
        $actor->approved = true;

        $club = Club::firstWhere(Club::NAME, '501st');

        $actor->attachClub($club->id, '99999', MembershipStatus::Member);

        $actor->save();
    }
}