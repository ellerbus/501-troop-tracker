<?php

declare(strict_types=1);

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

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Everglades Squad',
        ], [
            'image_path_lg' => 'images/everglades_emblem.png',
            'image_path_sm' => 'images/everglades_icon.png',
            'active' => true,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Makaze Squad',
        ], [
            'image_path_lg' => 'images/makaze_emblem.png',
            'image_path_sm' => 'images/makaze_icon.png',
            'active' => true,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Parjai Squad',
        ], [
            'image_path_lg' => 'images/parjai_emblem.png',
            'image_path_sm' => 'images/parjai_icon.png',
            'active' => true,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Squad 7',
        ], [
            'image_path_lg' => 'images/squad7_emblem.png',
            'image_path_sm' => 'images/squad7_icon.png',
            'active' => true,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Tampa Bay Squad',
        ], [
            'image_path_lg' => 'images/tampa_emblem.png',
            'image_path_sm' => 'images/tampa_icon.png',
            'active' => true,
        ]);
    }
}