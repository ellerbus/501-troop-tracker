<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:auto-accept-unconfirmed-events')
    ->weekly()
    ->sundays()
    ->at('02:00');
