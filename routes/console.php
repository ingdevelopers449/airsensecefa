<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programación de la Sincronización Automática Cloud-Local
Schedule::command('sync:telemetry --limit=30')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
