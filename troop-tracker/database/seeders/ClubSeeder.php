<?php

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Club;
use App\Models\Trooper;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Club::updateOrCreate([
            'name' => '501st',
        ], [
            'image_path_lg' => 'images/501st.png',
            'image_path_sm' => 'images/501st.png',
            'identifier_display' => 'TKID',
            'identifier_validation' => 'integer|between:1000,99999',
            'active' => true,
            'troopers_status_field' => 'p501',
            'troopers_identifier_field' => 'tkid',
            'troopers_notification_field' => 'esquad0',
            'troop_tracker_value' => 0,
        ]);
        Club::updateOrCreate([
            'name' => 'Rebel Legion',
        ], [
            'image_path_lg' => 'images/rebel-legion.png',
            'image_path_sm' => 'images/rebel-legion.png',
            'identifier_display' => 'Forum Username',
            'active' => true,
            'troopers_status_field' => 'pRebel',
            'troopers_identifier_field' => 'rebelforum',
            'troopers_notification_field' => 'esquad6',
            'troop_tracker_value' => 6,
        ]);
        Club::updateOrCreate([
            'name' => 'Droid Builders',
        ], [
            'troop_tracker_value' => 7,
            'image_path_lg' => 'images/droid-builders.png',
            'image_path_sm' => 'images/droid-builders.png',
            'identifier_display' => 'Forum Username',
            'active' => false,
            'troopers_status_field' => 'pDroid',
            'troopers_identifier_field' => 'droidid',
            'troopers_notification_field' => 'esquad7',
        ]);
        Club::updateOrCreate([
            'name' => 'Mando Mercs',
        ], [
            'image_path_lg' => 'images/mando-mercs.png',
            'image_path_sm' => 'images/mando-mercs.png',
            'identifier_display' => 'CAT #',
            'identifier_validation' => 'integer',
            'active' => true,
            'troopers_status_field' => 'pMando',
            'troopers_identifier_field' => 'mandoid',
            'troopers_notification_field' => 'esquad8',
            'troop_tracker_value' => 8,
        ]);
        Club::updateOrCreate([
            'name' => 'Saber Guild',
        ], [
            'image_path_lg' => 'images/saber-guild.png',
            'image_path_sm' => 'images/saber-guild.png',
            'identifier_display' => 'SG #',
            'identifier_validation' => 'integer',
            'active' => true,
            'troopers_status_field' => 'pSG',
            'troopers_identifier_field' => 'sgid',
            'troopers_notification_field' => 'esquad10',
            'troop_tracker_value' => 10,
        ]);
        Club::updateOrCreate([
            'name' => 'Dark Empire',
        ], [
            'image_path_lg' => 'images/saber-guild.png',
            'image_path_sm' => 'images/saber-guild.png',
            'identifier_display' => '#',
            'identifier_validation' => 'integer',
            'active' => true,
            'troopers_status_field' => 'pDE',
            'troopers_identifier_field' => 'de_id',
            'troopers_notification_field' => 'esquad13',
            'troop_tracker_value' => 13,
        ]);
    }
}