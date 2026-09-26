---
name: negocio
description: Resumen rápido del negocio Invicta Costa Rica (visitas, ventas, inventario, gastos, marketing). Úsalo cuando pregunten cuántas visitas/ventas hoy, cómo va el negocio, resumen del día, o necesites métricas sin abrir el admin.
---

# Negocio — resumen rápido

Fuente de verdad: `php artisan stats:today` (comando `App\Console\Commands\StatsToday`).
Úsalo SIEMPRE en vez de armar queries tinker a mano para el resumen diario.

## Comando

```bash
php artisan stats:today              # resumen de hoy, legible
php artisan stats:today --json       # salida JSON (para procesar o comparar días)
php artisan stats:today --date=2026-09-23   # otro día (YYYY-MM-DD)

php artisan stats:marketing --days=14   # marketing unificado por día (GA vs site vs Meta/Google Ads vs leads vs ventas)
php artisan stats:marketing --json
```

`stats:marketing` (`App\Console\Commands\StatsMarketing`) une por día: usuarios/sesiones de GA, visitantes del sitio, clics WhatsApp, gasto Meta Ads y Google Ads, leads creados (marcando los atribuidos a anuncio FB vía `fb_ad_id`), facturas y ventas. Ojo: "Site" cuenta todo el tráfico que ve el middleware (incluye bots sin JS); GA filtra bots, por eso GA suele ser menor.

## Qué devuelve

- **Visitas**: visitantes únicos del día (visto por middleware ese día o con eventos JS ese día), nuevos vs recurrentes, activos últimos 5 min (solo hoy), eventos por tipo (`page_view`, `product_view`, `search`, `whatsapp_click`, `add_to_cart`, `cta_click`) y top 5 modelos vistos.
- **Ventas**: facturas creadas hoy (conteo, monto, por estado), utilidad solo de `facturado` (`estimated_utility`), abonos del día, más acumulado del mes.
- **Inventario**: modelos activos, en stock, agotados, stock bajo (≤3), valor a costo y a venta.
- **Gastos**: hoy y mes (`expense_date`).
- **Marketing**: tareas pendientes, waitlist pendiente, clics WhatsApp hoy/mes, alertas activas.

## Definiciones (para no confundir al usuario)

- "Visitas hoy" = **visitantes únicos** con actividad hoy, NO suma de `visits_count` (ese contador sube solo si pasan 30+ min entre páginas, ver `App\Http\Middleware\TrackVisitor`).
- "Eventos" = filas en `visitor_events` de hoy (una visita genera varios).
- Utilidad = solo facturas `facturado`; los `apartado` aún no están cobrados.
- Sin facturas hoy es normal en días flojos: reporta el acumulado del mes como contexto.
- Timezone de la app: **UTC** (`config/app.timezone`). El corte "hoy" es 00:00 UTC = 6pm del día anterior en Costa Rica.

## Profundizar (solo si lo piden)

- Detalle de un visitante: ruta admin `admin/visitors` o modelo `App\Models\Visitor` + `VisitorEvent`.
- Ventas por período: `App\Livewire\Admin\Invoices` (filtros por fecha/estado).
- Dashboard completo con GA/Ads/Search Console: `App\Livewire\Admin\Dashboard`.
