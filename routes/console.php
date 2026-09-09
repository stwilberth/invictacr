<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sincroniza Google Analytics, Google Ads, Search Console, Facebook y GitHub
// una vez al día. Antes esto dependia de ejecucion manual y se dejo de correr
// tras la migracion del proyecto (dashboard mostraba caidas falsas por falta
// de datos, no por caida real de trafico/gasto).
Schedule::command('sync:all-analytics', ['--days' => 1])
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/sync-analytics-cron.log'));

// Publica en Facebook los relojes pendientes al azar (foto + texto + enlace).
// Ajustá --limit con el número de publicaciones diarias deseadas.
Schedule::command('campaigns:publish-facebook', ['--limit' => 3])
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/facebook-publish-cron.log'));
