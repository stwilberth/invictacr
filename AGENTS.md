# Instrucciones para el proyecto

## Convenciones del proyecto

## Stack y comandos útiles
- Laravel 13 + Vite + Tailwind CSS v4 (vía `@tailwindcss/vite`).
- Compilar assets: `npm run build` (salida en `public/build/`).
- Modo mantenimiento: `php artisan down --render="errors/503" --retry=60` / `php artisan up`.

## Verificación rápida de Blade
- Comprobar balance `@if`/`@endif`, `@push`/`@endpush`, `@auth`/`@endauth` (grep).

## Rutas y vistas clave
- Detalle de producto: `routes/web.php` -> `Route::get('/relojes/{slug}', [ProductController::class, 'show'])->name('products.show')`.
- Vista de producto: `resources/views/pages/product-detail.blade.php`.
- Catálogo: `resources/views/pages/catalog.blade.php`.

## Sincronización (manual, sin cron)
- No hay crons automáticos de stock/precios.
- La sincronización se ejecuta manualmente desde `/admin/sync` (botón "Sincronizar" → `VariedadesSyncService::execute()`).
- Los precios solo se recalculan en productos que cambiaron de stock y no están agotados (ver `app/Services/VariedadesSyncService.php`).

## Identidad visual / Diseño (resumen)
- Marca: navy + cian eléctrico — sobria y premium. Evitar gradientes genéricos y colores pastel.
- Colores principales: `#00C4FF` (acento), `#0a0f1c` (navy), `#121212` (fondo dark).
- Botones: `rounded-xl`, `font-extrabold`, `uppercase`, `hover:-translate-y-0.5`, `shadow-sm`.
- Dark mode: obligatoria; usar variantes `dark:` y persistir con `localStorage`.


## Google Analytics MCP (resumen de credenciales)
- Service account JSON: `storage/app/google-service-account-opencode.json`
- Cuenta: `opencode-wilberth@invictacr.iam.gserviceaccount.com`
- Proyecto GCP: `invictacr`
- Property ID GA4: `482259644`
- (Detalles operativos y comandos MCP se movieron a `docs/analytics-mcp.md` — mantener privado si contiene credenciales.)

## Al terminar cada tarea
- **SIEMPRE limpiar cache de Laravel**: `php artisan view:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear`
- **SIEMPRE commitear TODOS los cambios pendientes**: usar `git add -A` y commitear todo. Luego hacer push.
- Mensajes de commit: `tipo: descripción breve` (ej.: `feat: añadir X`). Usar `feat`, `fix`, `perf`.