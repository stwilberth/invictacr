# AGENTS.md — Invicta Costa Rica

Notas duras aprendidas en producción. Leer antes de tocar subidas de archivos,
imágenes o config de Livewire.

## Subida de archivos: usar formularios clásicos, NO Livewire

- Los `<input type="file" wire:model="...">` fallan en silencio: la subida JS se
  puede quedar trabada en "subiendo" para siempre, la propiedad llega `null` al
  servidor y la acción igual se ejecuta (ej.: abono creado sin comprobante).
- Para comprobantes y cualquier subida crítica usar **formulario clásico**
  (`method="POST" enctype="multipart/form-data"`) a una ruta de controlador
  (`app/Http/Controllers/Admin/InvoiceReceiptController.php` es el ejemplo).
  El navegador muestra progreso real y los errores de validación sí se ven.
- Nunca repetir el mismo `wire:model` en dos inputs de archivo renderizados a
  la vez (ej.: tabla desktop oculta + tarjeta móvil): traba la subida.

## config/livewire.php: NO tocar temporary_file_upload.rules por tipo

- `temporary_file_upload.rules` es **global**: restringirlo por mimetype
  (ej.: solo video) rompe TODAS las subidas del resto de la app (fotos de
  productos, comprobantes, etc.).
- Cada componente/controlador valida sus propios archivos (`mimes:...`,
  `max:...`). Dejar las reglas globales en default (`null`).
- Referencia: ReviewVideos sube directo a Cloudflare Stream sin pasar por
  Livewire, así que nunca necesitó esas reglas.

## R2: consistencia eventual en sobrescrituras

- Tras un `put()` que sobrescribe, `get()`/`size()` pueden devolver la versión
  vieja por minutos (HEAD y GET coinciden en lo viejo: los reintentos no sirven).
- Tras un scrape/descarga, generar derivados desde **los bytes recién
  descargados** (`ImageOptimizerService::optimizeProductFromContents()`),
  nunca releyendo de R2.
- Convención de rutas: en BD se guarda con prefijo `/storage/...`
  (ej.: `/storage/comprobantes/abonos/1.jpg`), pero la llave R2 es SIN ese
  prefijo (`comprobantes/abonos/1.jpg`). El CDN sirve la llave tal cual.

## Base de datos es producción

- Verificar con lecturas antes de escribir; limpiar toda fila/archivo de prueba
  (BD + R2) inmediatamente después de probar.
- No dejar archivos de test en `tests/`, ni caché de rutas (`route:clear`),
  ni cambios en `php.ini` sin avisar.
- Apache usa `mod_php` 8.3 (`/etc/php/8.3/apache2/php.ini`); php8.1-fpm NO
  sirve este sitio. Límites web actuales: `upload_max_filesize=20M`,
  `post_max_size=64M`.

## Resumen del negocio: usar el comando, no tinker a mano

- Para "visitas/ventas hoy" o resúmenes del negocio ejecutar
  `php artisan stats:today` (`--json`, `--date=YYYY-MM-DD` para otro día).
  Fuente: `app/Console/Commands/StatsToday.php`. Detalle en skill `negocio`
  (`.opencode/skills/negocio/SKILL.md`).
- "Visitas hoy" = visitantes únicos con actividad hoy; la app corre en UTC
  (corte 00:00 UTC = 6pm del día anterior en CR).

## Comandos y crons: ejecutarlos, no pedirlos

- Antes de pedirle al usuario que ejecute un comando (`php artisan ...`,
  `tinker`, `tail`, cron), el agente debe intentarlo él mismo con sus
  herramientas (bash, etc.) y mostrar el output real.
- Solo si falla por falta de acceso al servidor/producción, pedir al usuario
  el comando exacto listo para copiar/pegar y qué output se necesita de vuelta.
- Nunca dar una lista de comandos para que el usuario los corra sin haberlos
  intentado primero.
