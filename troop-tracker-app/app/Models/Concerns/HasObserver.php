<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasObserver
{
    /**
     * Bootstrap the trait.
     *
     * @return void
     */
    public static function bootHasObserver()
    {
        $name = Str::of(static::class)
            ->classBasename()
            ->value();

        $observer_class = "App\\Observers\\{$name}Observer";

        static::observe($observer_class);
    }
}
