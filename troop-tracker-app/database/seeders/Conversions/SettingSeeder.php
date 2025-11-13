<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacy_settings = DB::table('settings')->get();

        foreach ($legacy_settings as $setting)
        {
            DB::table('tt_settings')->insert([
                Setting::SUPPORT_GOAL => $setting->supportgoal,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}