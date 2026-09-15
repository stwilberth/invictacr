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

// Publica en Facebook los relojes pendientes (foto del canva + texto + enlace).
// 3 al día, uno en cada hora pico de Costa Rica. Cada publicado se marca en
// DownloadHistory y sale automáticamente de la lista de pendientes.
// OJO: el servidor corre en UTC; se fija la zona horaria para horas locales.
foreach (['08:00', '12:00', '18:00'] as $horaPico) {
    Schedule::command('campaigns:publish-facebook', ['--limit' => 1])
        ->dailyAt($horaPico)
        ->timezone('America/Costa_Rica')
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/facebook-publish-cron.log'));
}
