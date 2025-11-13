<?php

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\Costume;
use App\Models\Squad;
use App\Models\Trooper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class FloridaGarrisonSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $troopers = Trooper::all();

        $this->call(ClubSeeder::class);
        $this->call(SquadSeeder::class);

        $this->migrateCostumeClubs();
        $this->migrateTrooperClubs($troopers);
        $this->migrateTrooperSquads($troopers);
    }

    public function migrateTrooperClubs(Collection $troopers): void
    {
        $clubs = Club::all();

        foreach ($troopers as $trooper)
        {
            foreach ($clubs as $club)
            {
                $membership_status_field = $club->troopers_status_field;
                $trooper_identifier_field = $club->troopers_identifier_field;
                $troopers_notification_field = $club->troopers_notification_field;

                $identifier = $trooper->{$trooper_identifier_field};
                $membership_status = $trooper->{$membership_status_field};
                $notify = $trooper->{$troopers_notification_field};

                if ($membership_status != MembershipStatus::None && $identifier != null)
                {
                    $trooper->trooperClubs()->create([
                        'club_id' => $club->id,
                        'membership_status' => $membership_status,
                        'identifier' => $identifier,
                        'notify' => $notify
                    ]);
                }
            }
        }

        $this->command->info('✅ Trooper Clubs migrated successfully.');
    }

    public function migrateTrooperSquads(Collection $troopers): void
    {
        $squads = Squad::all();

        foreach ($troopers as $trooper)
        {
            $id = $trooper->squad;

            foreach ($squads as $squad)
            {
                $troopers_notification_field = $squad->troopers_notification_field;
                $notify = $trooper->{$troopers_notification_field};

                if ($notify)
                {
                    $trooper->trooperSquads()->create([
                        'squad_id' => $squad->id,
                        'notify' => $notify,
                        'membership_status' => $id == $squad->troop_tracker_value ? MembershipStatus::Member : MembershipStatus::None,
                    ]);
                }
            }
        }

        $this->command->info('✅ Trooper Squads migrated successfully.');
    }

    public function migrateCostumeClubs(): void
    {
        $clubs = Club::all();

        $costumes = Costume::all();

        foreach ($costumes as $costume)
        {
            $club = $clubs->firstWhere('troop_tracker_value', $costume->club);

            if ($club != null)
            {
                $costume->club_id = $club->id;

                $costume->save();
            }
        }

        $this->command->info('✅ Costume Club IDs migrated successfully.');
    }
}
