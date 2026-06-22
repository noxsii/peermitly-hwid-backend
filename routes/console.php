<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('subscriptions:expire')
    ->hourly()
    ->runInBackground()
    ->withoutOverlapping()
    ->onOneServer();
