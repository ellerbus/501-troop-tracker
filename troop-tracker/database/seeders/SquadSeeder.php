<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Squad;
use App\Models\Trooper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'troopers_notification_field' => 'esquad1',
            'troop_tracker_value' => 1,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Makaze Squad',
        ], [
            'image_path_lg' => 'images/makaze_emblem.png',
            'image_path_sm' => 'images/makaze_icon.png',
            'troopers_notification_field' => 'esquad2',
            'troop_tracker_value' => 2,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Parjai Squad',
        ], [
            'image_path_lg' => 'images/parjai_emblem.png',
            'image_path_sm' => 'images/parjai_icon.png',
            'troopers_notification_field' => 'esquad3',
            'troop_tracker_value' => 3,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Squad 7',
        ], [
            'image_path_lg' => 'images/squad7_emblem.png',
            'image_path_sm' => 'images/squad7_icon.png',
            'troopers_notification_field' => 'esquad4',
            'troop_tracker_value' => 4,
        ]);

        Squad::updateOrCreate([
            'club_id' => $club->id,
            'name' => 'Tampa Bay Squad',
        ], [
            'image_path_lg' => 'images/tampa_emblem.png',
            'image_path_sm' => 'images/tampa_icon.png',
            'troopers_notification_field' => 'esquad5',
            'troop_tracker_value' => 5,
        ]);
    }
}