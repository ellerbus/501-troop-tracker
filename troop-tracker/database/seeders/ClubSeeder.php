<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Club::create([
            'name' => '501st',
            'image_path' => 'images/clubs/501st.png',
            'db_status_field' => 'p501',
            'db_identifier_field' => 'tkid',
            'db_identifier_display' => 'TKID',
            'active' => true
        ]);
        Club::create([
            'name' => 'Rebel Legion',
            'image_path' => 'images/clubs/rebel-legion.png',
            'db_status_field' => 'pRebel',
            'db_identifier_field' => 'rebelforum',
            'db_identifier_display' => 'Rebel Legion Forum Username',
            'active' => true
        ]);
        Club::create([
            'name' => 'Droid Builders',
            'image_path' => 'images/clubs/droid-builders.png',
            'db_status_field' => 'pDroid',
            'db_identifier_field' => 'droidid',
            'db_identifier_display' => 'Rebel Legion Forum Username',
            'active' => false
        ]);
        Club::create([
            'name' => 'Mando Mercs',
            'image_path' => 'images/clubs/mando-mercs.png',
            'db_status_field' => 'pMando',
            'db_identifier_field' => 'mandoid',
            'db_identifier_display' => 'Mando Mercs CAT #',
            'active' => true
        ]);
        Club::create([
            'name' => 'Saber Guild',
            'image_path' => 'images/clubs/saber-guild.png',
            'db_status_field' => 'pSG',
            'db_identifier_field' => 'sgid',
            'db_identifier_display' => 'Saber Guild SG #',
            'active' => true
        ]);
        Club::create([
            'name' => 'Dark Empire',
            'image_path' => 'images/clubs/saber-guild.png',
            'db_status_field' => 'pDE',
            'db_identifier_field' => 'de_id',
            'db_identifier_display' => 'Dark Empire #',
            'active' => true
        ]);
    }
}
