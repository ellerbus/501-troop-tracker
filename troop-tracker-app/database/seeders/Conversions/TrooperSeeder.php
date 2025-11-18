<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\MembershipStatus;
use App\Enums\Permissions;
use App\Models\Trooper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrooperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacy_troopers = DB::table('troopers')->get();

        foreach ($legacy_troopers as $trooper)
        {
            $t = Trooper::find($trooper->id) ?? new Trooper(['id' => $trooper->id]);

            $t->name = $trooper->name;
            $t->phone = $trooper->phone;
            $t->username = $trooper->forum_id;
            $t->email = $trooper->email ?? '^' . uniqid();
            $t->password = $trooper->password ?? '^' . uniqid();

            $t->last_active_at = $trooper->last_active;
            $t->approved = $trooper->approved;
            $t->created_at = $trooper->datecreated;

            $t->instant_notification = $trooper->efast;
            $t->attendance_notification = $trooper->econfirm;
            $t->command_staff_notification = $trooper->ecommandnotify;

            $t->permissions = match ((int) $trooper->permissions)
            {
                0 => Permissions::Member,
                1 => Permissions::Admin,
                2 => Permissions::Moderator,
                3 => Permissions::Retired,
                default => Permissions::None,
            };

            $t->save();
        }
    }
}