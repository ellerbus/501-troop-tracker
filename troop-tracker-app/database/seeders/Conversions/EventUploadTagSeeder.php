<?php

declare(strict_types=1);

namespace Database\Seeders\Conversions;

use App\Enums\TrooperEventStatus;
use App\Models\ClubCostume;
use App\Models\Event;
use App\Models\EventTrooper;
use App\Models\EventUpload;
use App\Models\EventUploadTag;
use Database\Seeders\Conversions\Traits\HasSquadMaps;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventUploadTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacy_tags = DB::table('tagged')
            ->join('tt_event_uploads', 'tagged.photoid', '=', 'tt_event_uploads.id')
            ->join('tt_troopers', 'tagged.trooperid', '=', 'tt_troopers.id')
            ->select('tagged.*')
            ->get();

        foreach ($legacy_tags as $tag)
        {
            $e = EventUploadTag::find($tag->id) ?? new EventUploadTag(['id' => $tag->id]);

            $e->upload_id = $tag->photoid;
            $e->trooper_id = $tag->trooperid;

            $e->save();
        }
    }
}