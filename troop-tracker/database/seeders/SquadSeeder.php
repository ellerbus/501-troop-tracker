<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Squad;
use Illuminate\Database\Seeder;

class SquadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = Club::where('name', '501st')->first();

        if (!$club)
        {
            $this->command->warn('501st club not found. Skipping squad seeding.');
            return;
        }

        $squads = [
            ['name' => 'Everglades Squad', 'image_path' => 'logos/everglades.png'],
            ['name' => 'Makaze Squad', 'image_path' => 'logos/makaze.png'],
            ['name' => 'Parjai Squad', 'image_path' => 'logos/parjai.png'],
            ['name' => 'Squad 7', 'image_path' => 'logos/squad7.png'],
            ['name' => 'Tampa Bay Squad', 'image_path' => 'logos/tampa.png'],
        ];

        foreach ($squads as $squad)
        {
            Squad::create([
                'club_id' => $club->id,
                'name' => $squad['name'],
                'image_path' => $squad['image_path'],
            ]);
        }
    }
}
