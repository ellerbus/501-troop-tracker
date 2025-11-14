<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\ClubSeeder;
use Database\Seeders\Conversions\ClubCostumeSeeder;
use Database\Seeders\Conversions\DonationSeeder;
use Database\Seeders\Conversions\EventSeeder;
use Database\Seeders\Conversions\EventTrooperSeeder;
use Database\Seeders\Conversions\SettingSeeder;
use Database\Seeders\Conversions\TrooperClubSeeder;
use Database\Seeders\Conversions\TrooperCostumeSeeder;
use Database\Seeders\Conversions\TrooperSeeder;
use Database\Seeders\Conversions\TrooperSquadSeeder;
use Database\Seeders\SquadSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FloridaGarrisonSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ClubSeeder::class);
        $this->call(SquadSeeder::class);
        $this->call(ClubCostumeSeeder::class);

        $this->call(SettingSeeder::class);
        $this->call(TrooperSeeder::class);
        $this->call(TrooperClubSeeder::class);
        $this->call(TrooperSquadSeeder::class);
        $this->call(TrooperCostumeSeeder::class);
        $this->call(DonationSeeder::class);
        $this->call(TrooperSquadSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(EventTrooperSeeder::class);
    }
}
