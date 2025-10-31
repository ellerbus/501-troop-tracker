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
            'identifier_display' => 'TKID',
            'identifier_validation' => 'integer|between:1000,99999',
            'active' => true
        ]);
        Club::create([
            'name' => 'Rebel Legion',
            'image_path' => 'images/clubs/rebel-legion.png',
            'db_status_field' => 'pRebel',
            'db_identifier_field' => 'rebelforum',
            'identifier_display' => 'Forum Username',
            'active' => true
        ]);
        Club::create([
            'name' => 'Droid Builders',
            'image_path' => 'images/clubs/droid-builders.png',
            'db_status_field' => 'pDroid',
            'db_identifier_field' => 'droidid',
            'identifier_display' => 'Forum Username',
            'active' => false
        ]);
        Club::create([
            'name' => 'Mando Mercs',
            'image_path' => 'images/clubs/mando-mercs.png',
            'db_status_field' => 'pMando',
            'db_identifier_field' => 'mandoid',
            'identifier_display' => 'CAT #',
            'identifier_validation' => 'integer',
            'active' => true
        ]);
        Club::create([
            'name' => 'Saber Guild',
            'image_path' => 'images/clubs/saber-guild.png',
            'db_status_field' => 'pSG',
            'db_identifier_field' => 'sgid',
            'identifier_display' => 'SG #',
            'identifier_validation' => 'integer',
            'active' => true
        ]);
        Club::create([
            'name' => 'Dark Empire',
            'image_path' => 'images/clubs/saber-guild.png',
            'db_status_field' => 'pDE',
            'db_identifier_field' => 'de_id',
            'identifier_display' => '#',
            'identifier_validation' => 'integer',
            'active' => true
        ]);
    }
}
