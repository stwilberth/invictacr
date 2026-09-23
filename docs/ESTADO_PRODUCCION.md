# Estado de producción — Invicta Costa Rica
Actualizado: 2026-09-23. Leer desde Telegram con: "lee docs/ESTADO_PRODUCCION.md" (local) o "/root/ESTADO_PRODUCCION.md" (servidor).

## ✅ En producción y utilizable
- [x] **OpenCode servidor**: `opencode.json` reparado (4 modelos con `limit.output`), `opencode-serve` + 3 bots Telegram activos.
- [x] **SEO**: descriptions producto 596→158 chars, `/relojes` ampliada, canonical con `?gender=`, redirects 301 `http→https` y `www→apex`.
- [x] **OG images**: `/og/brand.png` funciona (fallback de fuente incluido).
- [x] **Tracking WhatsApp**: evento `Lead` con valor entra a `conversion_value`/ROAS sin duplicar. Verificado: lead $170.62.
- [x] **Tracking publicaciones**: posts FB llevan UTM (`utm_campaign=auto_feed`); `post_id` se guarda en `download_histories`.
- [x] **Panel Instagram**: `/admin/instagram` (perfil, 12 posts, alcance bajo demanda). `IG_ACCOUNT_ID` configurado.
- [x] **Token Meta nuevo**: instalado en local y servidor, conexión OK (23 permisos).

## ⏳ Pendiente / falta
- [ ] **Revocar token viejo de Meta**: está en `.env.bak-20260923` (local). Pegarlo en developers.facebook.com/tools/debug/accesstoken; si vive 60 días, quitar la app en facebook.com → Apps y sitios web (mata todos los tokens → generar otro y avisar para reinstalar).
- [ ] **Reactivar campañas Meta**: las 5 están pausadas, gasto $0. Plan: 1 CBO ventas $10-15/día × 7 días + retargeting.
- [ ] **Conversión offline real**: el ROAS actual es sobre *leads* de WhatsApp, no ventas cerradas. Importar a Meta las ventas confirmadas (contra entrega) para ROAS verdadero.
- [ ] **aggregateRating en schema**: omitido a propósito (no hay reseñas por producto; inventarlas penaliza).
- [ ] **Git**: commitear en local (panel IG, tracking, PublishFacebookPending, migración) y sincronizar servidor (`.htaccess`, `.env` con IG_ACCOUNT_ID no van a git).
- [ ] **Pre-existente sin commit** (no tocado): `package-lock.json`, `vite.config.js`, `plan_precios.md` eliminado.
- [ ] **HSTS** (opcional): no aplicado; los 301 ya resuelven lo crítico.
- [ ] **Prueba publicación auto FB/IG**: pausada. Comandos `campaigns:publish-facebook` y `campaigns:publish-story --channel=both` listos para dry-run.

## Backups en servidor (/root/)
`opencode.json.bak-20260923`, `seo_fix_bak_20260923`, `igdeploy_bak_20260923`, `env.bak-20260923`, `.htaccess.bak-20260923`.
