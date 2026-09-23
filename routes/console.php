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

// Historias de Facebook con el arte vertical 1080x1920 (sin caption ni enlace:
// todo va quemado en la imagen). 3 al día, 30 min después del post del feed en
// cada hora pico. Se registran en StoryHistory para no repetir modelos.
foreach (['08:30', '12:30', '18:30'] as $horaPico) {
    Schedule::command('campaigns:publish-story', ['--limit' => 3, '--channel' => 'facebook'])
        ->dailyAt($horaPico)
        ->timezone('America/Costa_Rica')
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/facebook-story-cron.log'));
}

// Historias de Instagram con el mismo arte vertical (la API exige URL pública:
// se sube el PNG a R2). Mismos horarios pico. Requiere IG_ACCOUNT_ID.
foreach (['08:30', '12:30', '18:30'] as $horaPico) {
    Schedule::command('campaigns:publish-story', ['--limit' => 3, '--channel' => 'instagram'])
        ->dailyAt($horaPico)
        ->timezone('America/Costa_Rica')
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/instagram-story-cron.log'));
}

// Sincroniza vistas/alcance/reacciones de las historias publicadas en FB e IG.
// Las historias expiran a las 24h y la API deja de devolver datos, así que
// corre cada hora (ventana 24h) para no perder ninguna tanda.
Schedule::command('campaigns:fetch-story-insights')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/story-insights-cron.log'));

// Verifica salud de APIs externas cada 6 horas. Genera alertas en el dashboard
// si Google Analytics, Google Ads, Meta Ads o Search Console no han sincronizado
// en más de 3 días. Envía webhook si está configurado (ALERTS_WEBHOOK_URL).
Schedule::command('app:check-api-health', ['--threshold' => 3])
    ->everySixHours()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/check-api-health-cron.log'));
