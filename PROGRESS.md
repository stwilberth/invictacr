# PROGRESS.md — Invicta Costa Rica

> Punto de recuperación de contexto. Léeme primero en cada sesión nueva para evitar releer el código.
> Última actualización: 2026-07-30

---

## 1. Proyecto

E-commerce de relojes **Invicta Costa Rica** (`https://invictacostarica.com`).
Stack: **Laravel 13 + Vite + Tailwind v4 (vía `@tailwindcss/vite`) + Livewire 3**.
BD: MySQL local (`invictacr`, user `root`). Imágenes servidas desde CDN propio `https://cdn.invictacostarica.com` (migración R2 → CDN, ver `R2_MIGRATION.md`).
Entorno: **producción** (`APP_DEBUG=false`, mantenimiento con `php artisan down`).

---

## 2. Estructura clave

```
routes/web.php              — todas las rutas (home, /relojes, /admin/*, etc.)
app/Http/Controllers/       — 13 controllers (Home, Product, Cart, Checkout, PayPal, OrderTracking, Dashboard, InvoicePdf, Profile, Search, Subscriber + Api/)
app/Livewire/Admin/         — 22 componentes Livewire admin
app/Models/                 — 32 modelos (Product, Invoice, Client, Visitor, SyncLog, GoogleAnalyticsReport, etc.)
app/Console/Commands/       — 16 comandos artisan (sync:google-analytics, sync:facebook, sync:github, etc.)
resources/views/pages/      — 16 vistas públicas (home, catalog, product-detail, cart, checkout, etc.)
resources/views/livewire/admin/ — 22 vistas Livewire (mismo nombre que el componente)
resources/views/components/ — 12 componentes Blade (navbar, footer, product-card, filter-form, etc.)
database/migrations/        — esquema versionado (último: visitors/visitor_events)
```

### Rutas admin (todas `auth` + `admin`)
- `/admin/dashboard` → `Dashboard` (métricas ventas, GA, FB, sync, **herramientas dev** con systemctl code-server/opencode-web)
- `/admin/products`, `/admin/products/{create|{id}/edit}` → `Products` + `ProductForm`
- `/admin/invoices`, `/invoices/create`, `/invoices/{id}` → `Invoices`, `InvoiceCreate`, `InvoiceDetail`
- `/admin/clients`, `/users`, `/subscribers`, `/expenses`, `/marketing`, `/campaigns`, `/upcoming`
- `/admin/sync` (SyncManager), `/search-logs`, `/optimize-images`, `/db-backups`
- `/admin/analytics` (AnalyticsDashboard), `/visitors`, `/visitors/{id}`
- `/admin/timeline` (UnifiedTimeline), `/github` (GitHubReport)

### Rutas públicas
- `GET /` → home (con form newsletter + Turnstile)
- `GET /relojes` (catálogo con throttle:search), `/relojes/{gender}`, `/relojes/{slug}` (detalle)
- `/carrito`, `/checkout` (auth), `/paypal/*` (auth)
- `/mis-pedidos` (tracking público)
- Páginas info: `/como-comprar`, `/formas-pago`, `/informacion-de-envio`, `/garantia`, `/resistencia-agua`, `/resenas`, `/sobre-nosotros`, `/privacidad`
- `POST /subscribe` (newsletter), `POST /track/event`, `/track/heartbeat` (visitor tracking)
- `GET /sitemap.xml`

---

## 3. Decisiones / convenciones asentadas

### Producto
- Modelo `Product` (fillable): `modelo, title, slug, descripcion, color, brazalete, coleccion, tipo_movimiento, size, genero, caja, resistencia_agua, video, precio_venta, precio_original, descuento, stock, disponibilidad, imagen, activo, caracteristicas, vistas, bloqueado, proximo`.
- Casts: `bloqueado/proximo/activo` boolean, `caracteristicas` array, precios `decimal:2`.
- **Accessor `getImagenAttribute`**: si es ruta relativa `/storage/...` o `/...` la reescribe a `https://cdn.invictacostarica.com/...`. **Mutator** revierte el prefijo CDN al guardar.
- Estados de venta en detalle (`product-detail.blade.php`):
  - `$isAgotado` = `disponibilidad === 'agotado' || stock <= 0`
  - `$isUpcoming` = `proximo === true || precio_venta <= 0`

### Catálogo / cards
- **Título unificado** en DB, cards y product-detail (mismo helper).
- Cards sin bordes/fondo, precio centrado, etiqueta `Automático` sobre imagen (negro/amarillo hex explícito para dark mode).
- Botón "Ver más" celeste con texto negro, outline en "Ver video" y "Ver más".
- Búsqueda **tokenizada por title** en las 3 ubicaciones (home, navbar, mobile).
- **Normalización de colección** en inputs: prevenir suciedad (variantes, mayúsculas, sinónimos) → comando `NormalizeCollections` + helpers.
- Filtros: `bloqueado` y `brazalete` en admin products / campaigns.

### Invoice (factura)
- **Join `invoice_items` por `product_model` (no `product_id`)** porque el campo puede ser null.
- Inputs numéricos con `wire:model.blur` para evitar 419 TypeError.
- Apartado: muestra **total abonado** y **saldo pendiente**.
- Delete: confirmación con **SweetAlert** (no `wire:confirm`).
- Top selling: **colecciones** más vendidas (no productos) y sin filtro de fecha.

### Dashboard admin
- Métricas del mes: revenue, count facturas, ticket medio, productos activos, low/out stock, upcoming, visitors hoy, WhatsApp clicks, suscriptores mes.
- Top 10 colecciones, 5 facturas recientes, 5 suscriptores, 5 FB posts, 30 días GA, traffic sources.
- **Card "Herramientas dev"**: estado + start/stop de `code-server@bitnami` y `opencode-web` vía `sudo -n systemctl` (whitelist de units/acciones).
- Botón **sync**: corre 6 comandos artisan (GA, Google Ads, Search Console, FB posts, FB ads, GitHub).

### Suscriptores
- Form público con **Cloudflare Turnstile** (home).
- Admin: tabla con toggle activo, delete, search, filtros.

### Visitor tracking
- `POST /track/event` (throttle 60/min) y `/track/heartbeat` (120/min) — sin CSRF (fetch/sendBeacon).
- Modelos `Visitor` + `VisitorEvent`.

---

## 4. Últimos 30 commits (resumen)

1. **Dashboard dev tools card** + soporte systemctl.
2. Columna `modelos` con links en tabla de facturas.
3. Campo `disponibilidad` en product form + reset al restaurar stock.
4. Filtro `bloqueado` en admin products.
5. Sinónimos de búsqueda con nombre canónico.
6. Normalización de colecciones (input + DB).
7. Mayúscula en columna colección.
8. Indicador de video en products table.
9. Fix 419 con `wire:model.blur` + manejo de inputs numéricos vacíos.
10. Newsletter con Turnstile + admin subscribers.
11. Join `invoice_items` por `product_model`.
12. Colecciones más vendidas (sin filtro fecha) en dashboard.
13. Top colecciones en vez de top products.
14. Filtro brazalete en campaigns.
15. Dashboard expandido (métricas ventas, GA, FB, WhatsApp, sync).
16. Total abonado / saldo en apartado.
17. SweetAlert para delete de factura.
18. Botón delete en invoice detail.
19. Sección admin users (paginación, search, filtros).
20. Accesor `getImagenAttribute` para rutas relativas.

Total commits en repo: **194** (~30 días).

---

## 5. TAREAS PENDIENTES (no hechas)

### Detectadas por inspección rápida (verificar con el usuario)
- [ ] **Migrar BD a hosting real**: el `.env` apunta a `127.0.0.1` local con password `4m.02hrhX6Xw` — **NO subir a git**.
- [ ] **Subir `public/build/` al servidor** o configurar build en deploy (actualmente está en `.gitignore`).
- [ ] **Verificar que el CDN `cdn.invictacostarica.com`** responde 200 en todas las rutas de imagen (auditoría con `OptimizeImages`).
- [ ] **Tests**: `phpunit.xml` existe pero revisar cobertura real (`tests/`).
- [ ] **PSRs / lint**: no hay `composer.json` scripts visibles para php-cs-fixer o psalm.
- [ ] **Política de backups**: existe `DbBackups` admin pero confirmar cron real.
- [ ] **Cron de syncs**: `sync:google-analytics`, `sync:facebook`, etc. — confirmar entrada en `app/Console/Kernel.php` o servidor.
- [ ] **Privacy policy** (`privacidad.blade.php`) — revisar que cumple Ley 8968 Costa Rica.
- [ ] **SEO**: sitemap.xml existe, falta verificar OG tags, schema.org Product en detail.
- [ ] **Reseñas** (`resenas.blade.php`, `ProductComment` model) — confirmar integración real.
- [ ] **2FA / recuperación de password** para admin.

### Por confirmar con el usuario
- [ ] ¿Hay tareas específicas del sprint actual fuera de este análisis?

---

## 6. Comandos frecuentes

```bash
# Build frontend (genera public/build/)
npm run build

# Limpiar cache Laravel tras editar Blade/config
php artisan view:clear && php artisan cache:clear && php artisan config:clear && php artisan route:clear

# Modo mantenimiento (503 personalizada)
php artisan down --render="errors/503" --retry=60
php artisan up

# Validar sintaxis Blade rápido
php -l resources/views/pages/product-detail.blade.php

# Verificar balance de directivas Blade
grep -cE '@if|@endif|@push|@endpush|@auth|@endauth' resources/views/pages/product-detail.blade.php

# Probar en local con HTTPS + host real
curl -k -H "Host: invictacostarica.com" https://127.0.0.1/...

# Comandos de sync (los lanza el botón del dashboard)
php artisan sync:google-analytics --days=7
php artisan sync:google-ads --days=7
php artisan sync:search-console --days=7
php artisan sync:facebook --days=7 --posts=10
php artisan sync:facebook-ads --days=7
php artisan sync:github

# Normalizaciones de datos
php artisan normalize:collections
php artisan normalize:colores
php artisan normalize:caja
php artisan normalize:movimiento
```

---

## 7. Errores / gotchas aprendidos

1. **`wire:model` en inputs numéricos vacíos → 419 Page Expired** → usar `wire:model.blur` y validar `filled()` antes de `intval()`.
2. **`invoice_items.product_id` puede ser NULL** → siempre join por `product_model` (string `modelo`).
3. **Accesor de imagen** debe manejar **3 casos**: URL completa, `/storage/...`, ruta relativa `/relojes/...` — todas reescritas al CDN.
4. **Mutator inverso** al accessor para no duplicar prefijo CDN al guardar.
5. **Tailwind v4 dark mode**: hex amarillo explícito en lugar de `bg-yellow-*` para etiqueta "Automático" (cambio de color no se propagaba).
6. **SweetAlert para confirmaciones destructivas** (no `wire:confirm` que es inconsistente entre versiones).
7. **Slug en redirección 301**: `/{gender}/{slug}` → `/relojes/{slug}` evita contenido duplicado.
8. **Sinónimos de búsqueda**: usar nombre canónico (`SearchService`) para que "INVICTA 8926" y "8926OB" mapeen al mismo grupo.
9. **`proximo` vs `precio_venta <= 0`**: ambos ocultan precio → lógica OR en `$isUpcoming`.
10. **`recentSyncs`** siempre castear a `array()` antes de pasar a la vista (era un Collection y rompía la propiedad tipada `array`).

---

## 8. Al retomar en nueva sesión

1. Leer este archivo completo.
2. `git status && git log --oneline -10` para ver cambios sin commitear.
3. Confirmar con el usuario: **¿en qué tarea continuamos?** (ver sección 5).
4. Si hay cambios en Blade → `php artisan view:clear` y renderizar con `curl -k -H "Host: invictacostarica.com" https://127.0.0.1/...`.
5. Si hay cambios en JS/CSS → `npm run build` y verificar `public/build/`.

---

## 9. Archivos a NUNCA commitear

- `.env` (secretos DB)
- `public/build/` (generado, está en `.gitignore`)
- `storage/logs/*`
- `.phpunit.result.cache`

Mantener `.env.bak` y `.env.example` sí.
