<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Club;
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
        ]);
        Club::updateOrCreate([
            'name' => 'Rebel Legion',
        ], [
            'image_path_lg' => 'images/rebel-legion.png',
            'image_path_sm' => 'images/rebel-legion.png',
            'identifier_display' => 'Forum Username',
            'active' => true,
        ]);
        Club::updateOrCreate([
            'name' => 'Droid Builders',
        ], [
            'image_path_lg' => 'images/droid-builders.png',
            'image_path_sm' => 'images/droid-builders.png',
            'identifier_display' => 'Forum Username',
            'active' => false,
        ]);
        Club::updateOrCreate([
            'name' => 'Mando Mercs',
        ], [
            'image_path_lg' => 'images/mando-mercs.png',
            'image_path_sm' => 'images/mando-mercs.png',
            'identifier_display' => 'CAT #',
            'identifier_validation' => 'integer',
            'active' => true,
        ]);
        Club::updateOrCreate([
            'name' => 'Saber Guild',
        ], [
            'image_path_lg' => 'images/saber-guild.png',
            'image_path_sm' => 'images/saber-guild.png',
            'identifier_display' => 'SG #',
            'identifier_validation' => 'integer',
            'active' => true,
        ]);
        Club::updateOrCreate([
            'name' => 'Dark Empire',
        ], [
            'image_path_lg' => 'images/saber-guild.png',
            'image_path_sm' => 'images/saber-guild.png',
            'identifier_display' => '#',
            'identifier_validation' => 'integer',
            'active' => true,
        ]);
        Club::updateOrCreate([
            'name' => 'Other',
        ], [
            'image_path_lg' => 'images/other.png',
            'image_path_sm' => 'images/other.png',
            'identifier_display' => '#',
            'identifier_validation' => 'integer',
            'active' => false,
        ]);
    }
}