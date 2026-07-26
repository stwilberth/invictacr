# Instrucciones para el proyecto

## Convenciones del proyecto

### Git
- **SIEMPRE commitear TODOS los cambios pendientes**, no solo los archivos que modifiqué. Al terminar una tarea, hacer `git add -A` y commitear todo (incluyendo cambios que haya encontrado en el working tree).
- **SIEMPRE hacer push** después de commitear (el usuario lo confirma, pero commitear todo es automático).
- Estilo de mensajes de commit: `tipo: descripción breve` (minúsculas, sin punto final). Tipos usados: `feat`, `fix`, `perf`. Cuerpo opcional con guion medieval de detalles.
- No crear commits vacíos, no amend, no force-push.
- Verificar `git status` y `git diff` antes de commitear; excluir solo secretos.

## Stack
- Laravel 13 + Vite + Tailwind CSS v4 ( vía `@tailwindcss/vite` )
- Compilar CSS/JS con `npm run build` (output en `public/build/`, está en `.gitignore`)
- Limpiar vistas con `php artisan view:clear` tras editar Blade
- Modo mantenimiento: `php artisan down --render="errors/503" --retry=60` / `php artisan up`

## Verificación de cambios Blade
- Comprobar balance `@if`/`@endif`, `@push`/`@endpush`, `@auth`/`@endauth` con grep
- `php -l` sobre el archivo Blade para sintaxis
- Renderizar en vivo vía curl a `https://127.0.0.1` con `Host: invictacostarica.com` (HTTPS, `-k`)
- Hay una 503 personalizada en `resources/views/errors/503.blade.php`

## Rutas clave
- Detalle de producto: `routes/web.php` -> `Route::get('/relojes/{slug}', [ProductController::class, 'show'])->name('products.show')`
- Vista: `resources/views/pages/product-detail.blade.php`
- Catálogo: `resources/views/pages/catalog.blade.php`
- Componente tarjeta relacionada: `resources/views/components/product-card-related.blade.php`

## Datos del producto
- Agotado: `disponibilidad = 'agotado'` o `stock <= 0`
- Próximo: `proximo = true` o `precio_venta <= 0`
- En vistas: `$isAgotado` y `$isUpcoming` (definidos en product-detail.blade.php)

## Al terminar cada tarea
- **SIEMPRE limpiar cache de Laravel**: `php artisan view:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear`