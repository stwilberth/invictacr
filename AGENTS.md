# Instrucciones para el proyecto

## Convenciones del proyecto

### Git
- **SIEMPRE commitear TODOS los cambios pendientes**, no solo los archivos que modifiqué. Al terminar una tarea, hacer `git add -A` y commitear todo (incluyendo cambios que haya encontrado en el working tree).
- **SIEMPRE hacer push** después de commitear (el usuario lo confirma, pero commitear todo es automático).
- Estilo de mensajes de commit: `tipo: descripción breve` (minúsculas, sin punto final). Tipos usados: `feat`, `fix`, `perf`. Cuerpo opcional con guion medieval de detalles.
- No crear commits vacíos, no amend, no force-push.
- Verificar `git status` y `git diff` antes de commitear; excluir solo secretos.

## Economía de tokens
- **No leer archivos de más.** Antes de explorar, revisar primero si la info ya está en este AGENTS.md (rutas clave, identidad visual, comandos, datos del producto).
- Usar `grep`/búsquedas puntuales en vez de leer archivos completos cuando solo se necesita una sección o confirmar algo puntual.
- No lanzar tareas de exploración (Task/agent explorador) para cosas ya documentadas aquí; solo explorar lo que realmente falta.
- Al editar, leer únicamente el archivo(s) que se va a modificar, no archivos relacionados "por si acaso".

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

## Identidad visual / Diseño

**NO usar gradientes genéricos (ámbar/naranja/esmeralda) ni estilo "AI slop".** Esta es una marca navy + cian eléctrico, sobria y premium (relojes de noche/tech), no colores pastel ni degradados alegres.

### Colores de marca
| Color | Hex | Uso |
|---|---|---|
| **Acento primario (cian eléctrico)** | `#00C4FF` | Color de marca principal — CTAs, links, estados activos de nav, logo |
| Acento hover/oscuro | `#00a3d6` (también `#00b0e6`, `#00b4d8`) | hover de botones cian |
| **Navy oscuro (superficie/bg)** | `#0a0f1c` | Fondo navbar, menú móvil, banner en dark mode |
| Navy secundario | `#0f172a` | Dropdowns, buscador móvil |
| Fondo dark mode real | `#121212` | `body` en modo oscuro (`--bg-color` en app.css) |
| WhatsApp verde | `#25D366` (hover `#20bd5a`) | Botón/ícono de WhatsApp únicamente |
| Rojo precio/descuento | Tailwind `red-500`/`red-600` o `#dc2626` | Precios (siempre en rojo, NO cian), badges de descuento, estado "Agotado" |

- No hay `tailwind.config.js`: Tailwind v4 con `@theme` en `app.css` (config CSS-first).
- No custom Google Font: stack de fuentes del sistema (`ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto...`).
- Tipografía: `font-black`/`font-extrabold` + `uppercase` + `tracking-tight` en botones/CTAs y encabezados — estilo contundente, no suave.

### Convenciones de botones
- Border-radius: `rounded-xl` (mayoría de CTAs) o `rounded-2xl` (promos grandes). Nunca `rounded-full` en botones primarios (los pills son solo para badges/avatares de ícono).
- Sombras sutiles: `shadow-sm hover:shadow-md`, nunca sombras dramáticas.
- Micro-interacción estándar: `hover:-translate-y-0.5 active:scale-95`.
- Patrón botón primario:
  ```html
  class="bg-[#00C4FF] hover:bg-[#00a3d6] text-white rounded-xl font-extrabold uppercase tracking-tight text-xs transition-all hover:-translate-y-0.5 active:scale-95 shadow-sm hover:shadow-md"
  ```
- Botón sobre fondo cian: el texto va en `text-[#0a0f1c]` (navy oscuro), NO blanco — es una elección deliberada de contraste (ver cookie-banner).
- Badges de descuento: bajo radius (`rounded`, no `rounded-full`), `bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5`.
- Badges de estado (Agotado/Próximamente): sí usan `rounded-full` con fondo tintado suave (`bg-amber-50 dark:bg-amber-900/20 border border-amber-200`).

### Dark mode — obligatorio en todo componente nuevo
- Toggle vía Alpine.js, persistido en `localStorage.getItem('theme')`, aplica clase `.dark` en `<html>` (se setea antes del paint para evitar flash).
- `body`: `bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-100`
- Tarjetas: `bg-white dark:bg-gray-800`, bordes `border-gray-100 dark:border-gray-700` (o `dark:border-white/10` en zonas navy).
- **Todo componente nuevo debe incluir variantes `dark:`**, sin excepción.

### Referencia: banner fijo (cookie-banner.blade.php)
Patrón a replicar para cualquier popup/banner fijo nuevo:
```html
<div class="fixed bottom-0 left-0 right-0 z-[90] bg-white/95 dark:bg-[#0a0f1c]/95 backdrop-blur-md border-t border-gray-200 dark:border-white/10 shadow-[0_-10px_30px_rgba(0,0,0,0.15)]">
    ...
    <button class="px-4 py-2 text-xs sm:text-sm font-bold rounded-xl bg-[#00C4FF] hover:bg-[#00b0e6] text-[#0a0f1c] transition-colors shrink-0">
```
- Fondo: blanco/navy semi-transparente con `backdrop-blur-md` (glassmorphism sobrio, no gris genérico).
- Borde: hairline 1px, `gray-200` claro / `white/10` oscuro — nunca borde de color o degradado.
- Sombra: rgba cruda direccional (`shadow-[0_-10px_30px_rgba(0,0,0,0.15)]`), no utilidad Tailwind default.
- z-index: `z-[90]` para UI de nivel banner (navbar `z-[60]`, dropdowns `z-50`).

## Comandos CLI

### Actualizar precios
- `php artisan invicta:update-prices {modelo=precio...} [--dry-run]`
- Ejemplos:
  - `php artisan invicta:update-prices --dry-run 50638=55000 49573=69000` (simular)
  - `php artisan invicta:update-prices 50638=55000 49573=69000` (aplicar + limpiar caché)
- Ejecutar como `sudo -u daemon` para que tenga permisos de escritura en cache.
- Solo modifica `precio_venta`, no toca nombre, descripción, imágenes ni stock.

## Al terminar cada tarea
- **SIEMPRE limpiar cache de Laravel**: `php artisan view:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear`