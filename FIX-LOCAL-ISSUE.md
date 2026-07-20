# Problema local: Invalid route action

## Error

```
UnexpectedValueException: Invalid route action: [App\Livewire\Admin\UnifiedTimeline].
```

Se ejecuta al correr cualquier comando de artisan que cargue rutas (`optimize:clear`, `route:list`, etc).

## Causa

En `routes/web.php` linea 81:

```php
Route::get('/timeline', \App\Livewire\Admin\UnifiedTimeline::class)->name('timeline');
```

El componente Livewire `App\Livewire\Admin\UnifiedTimeline` **existe** en `app/Livewire/Admin/UnifiedTimeline.php` y la vista existe en `resources/views/livewire/admin/unified-timeline.blade.php`.

Sin embargo, en local el error persiste probablemente por una de estas razones:

1. **Caché de rutas desactualizado** — Las rutas cacheadas apuntan a una versión vieja del componente que no existia. Solucion:

```bash
php artisan optimize:clear
php artisan route:cache
```

2. **El componente no tiene layout válido** — En `UnifiedTimeline.php` linea 289 se usa:

```php
->layout('components.admin-layout', ['title' => 'Timeline Unificado']);
```

Verificar que `resources/views/components/admin-layout.blade.php` exista y sea un layout Blade válido (no un componente Livewire). Si el layout no existe o tiene errores, la ruta falla al cargar.

3. **Dependencias faltantes** — El componente usa estos modelos (verificar que existan y tengan sus migraciones ejecutadas):
   - `App\Models\AiTimelineInsight`
   - `App\Models\ExternalFactor`
   - `App\Models\FacebookAdReport`
   - `App\Models\FacebookInsight`
   - `App\Models\FacebookPost`
   - `App\Models\GitHubCommit`
   - `App\Models\GoogleAdsReport`
   - `App\Models\GoogleAnalyticsReport`
   - `App\Models\Invoice`
   - `App\Models\SearchConsoleReport`
   - `App\Services\TimelineAiService`

   Si falta alguno, artisan puede fallar al registrar las rutas.

## Pasos para resolver

1. Ejecutar `php artisan optimize:clear` para limpiar toda caché
2. Ejecutar `php artisan route:list` y verificar que no hay errores
3. Si el error persiste, ejecutar `php artisan migrate` para asegurar que todas las tablas existan
4. Si sigue fallando, verificar que todos los modelos listados arriba existan en `app/Models/`
5. Como ultimo recurso, comentar la ruta en `routes/web.php:81` si no se necesita aun:
   ```php
   // Route::get('/timeline', \App\Livewire\Admin\UnifiedTimeline::class)->name('timeline');
   ```

En produccion no falla porque las rutas estan cacheadas desde antes de que se agregara esta ruta. Al hacer deploy con `php artisan optimize` o `route:cache`, el error podria aparecer.
