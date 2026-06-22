<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('license-keys:expire')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer();
