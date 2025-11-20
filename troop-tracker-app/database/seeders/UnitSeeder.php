<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // 501st Legion – Florida Garrison
            ['region' => 'Florida Garrison', 'name' => 'Everglades Squad'],
            ['region' => 'Florida Garrison', 'name' => 'Makaze Squad'],
            ['region' => 'Florida Garrison', 'name' => 'Tampa Bay Squad'],
            ['region' => 'Florida Garrison', 'name' => 'Squad 7'],
            ['region' => 'Florida Garrison', 'name' => 'Parjai Squad'],

            // Rebel Legion – Ra Kura Base

            // Mandalorian Mercs – House Buurenaar Verda
            ['region' => 'House Buurenaar Verda', 'name' => 'Aiwha Riders Clan'],
            ['region' => 'House Buurenaar Verda', 'name' => 'Batuu Clan'],
            ['region' => 'House Buurenaar Verda', 'name' => 'Drexl Clan'],
            ['region' => 'House Buurenaar Verda', 'name' => 'Scarif Clan'],

            // Dark Empire – Florida
            ['region' => 'Dark Empire Florida', 'name' => 'Shadow Cell'],

            // Saber Guild – Talon Temple
            ['region' => 'Saber Guild - Talon Temple', 'name' => 'Performance Team'],

            // Droid Builders – Florida
            ['region' => 'Florida Droid Builders', 'name' => 'R2 Builders Tampa'],
            ['region' => 'Florida Droid Builders', 'name' => 'R2 Builders Orlando'],
        ];

        foreach ($units as $data)
        {
            $region = Region::where('name', $data['region'])->first();

            if ($region)
            {
                Unit::updateOrCreate(
                    ['name' => $data['name'], 'region_id' => $region->id],
                    ['active' => true] // Add additional fields here if needed
                );
            }
        }

    }
}
