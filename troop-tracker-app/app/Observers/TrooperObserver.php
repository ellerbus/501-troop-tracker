<?php

namespace App\Observers;

use App\Models\Trooper;
use App\Models\TrooperAchievement;

class TrooperObserver
{
    /**
     * Handle the Trooper "created" event.
     */
    public function created(Trooper $trooper): void
    {
        TrooperAchievement::create([
            'trooper_id' => $trooper->id,
        ]);
    }

    /**
     * Handle the Trooper "updated" event.
     */
    public function updated(Trooper $trooper): void
    {
        //
    }

    /**
     * Handle the Trooper "deleted" event.
     */
    public function deleted(Trooper $trooper): void
    {
        //
    }

    /**
     * Handle the Trooper "restored" event.
     */
    public function restored(Trooper $trooper): void
    {
        //
    }

    /**
     * Handle the Trooper "force deleted" event.
     */
    public function forceDeleted(Trooper $trooper): void
    {
        //
    }
}
