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
        $settings = DB::table('settings')->get();

        foreach ($settings as $setting)
        {
            $s = Setting::first();

            if ($s == null)
            {
                $s = new Setting();
            }

            $s->support_goal = $setting->supportgoal;

            $s->save();
        }
    }
}