# Optimización de Rendimiento - Guía para IA de Producción

## Datos del Lighthouse (Producción)

### Página principal: https://invictacostarica.com/relojes

| Métrica | Valor Actual | Objetivo |
|---|---|---|
| Performance Score | 86 | ≥ 90 |
| FCP | 2.1s | ≤ 1.8s |
| LCP | 3.4s | ≤ 2.5s |
| TBT | 240ms | ≤ 200ms |
| CLS | 0 | 0 |
| Speed Index | 2.2s | ≤ 2.0s |

### Página detalle: https://invictacostarica.com/relojes/invicta-37432

| Métrica | Valor Actual | Objetivo |
|---|---|---|
| Performance Score | 70 | ≥ 90 |
| FCP | 1.8s | ≤ 1.8s |
| LCP | 7.7s | ≤ 2.5s |
| TBT | 210ms | ≤ 200ms |
| CLS | 0 | 0 |
| Speed Index | 3.4s | ≤ 2.0s |

---

## Problemas identificados y soluciones

### 1. Imagen principal del detalle (334 KiB, LCP 7.7s)

La imagen del reloj (`/relojes/37432.jpg`) es el LCP de la página detalle pero carga muy tarde.

**Solución:** En `resources/views/pages/product-detail.blade.php`:
- Agregar `<link rel="preload" as="image" href="...">` para la imagen LCP en el `<head>`
- Usar `fetchpriority="high"` en el `<img>` del LCP
- Cambiar `loading="lazy"` a `loading="eager"` solo para la imagen LCP

### 2. Cache TTL 4h en todos los assets (579 KiB)

Cloudflare está aplicando TTL de 4h por defecto en vez de 1 año. Los headers `.htaccess` que agregamos no se están respetando.

**Solución en Cloudflare Dashboard:**
1. Ir a **Caching → Configuration**
2. **Browser Cache TTL** → cambiar a **"Respect existing headers"** o **1 año**
3. Verificar que no haya Page Rules sobreescribiendo el cache

**Solución en `.htaccess`** (ya la teníamos, se revirtió):
```apache
<IfModule mod_headers.c>
    <FilesMatch "\.(woff2?|ttf|otf|eot)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>
    <FilesMatch "-[A-Za-z0-9_-]{8,}\.(css|js)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>
    <FilesMatch "\.(jpe?g|png|gif|webp|avif|svg|ico)$">
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"
</IfModule>
```

### 3. Font Awesome carga bloqueante (580ms savings)

Las fuentes de Font Awesome (`fa-solid-900.woff2` y `fa-brands-400.woff2`) cargan desde cdnjs sin `font-display: swap`.

**Solución en `resources/views/components/app-layout.blade.php`:**
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" media="print" onload="this.media='all'" />
```
Esto ya está así (non-blocking). El problema es que Lighthouse lo marca porque las fuentes .woff2 dentro del CSS CDN no tienen `font-display: swap`. No se puede controlar desde nuestro lado, pero sí se puede hacer preload:
```html
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin />
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin />
```

### 4. GTM y FB Pixel cargan de inmediato (450 KiB JS sin usar)

Se recomienda diferir su carga hasta la primera interacción del usuario.

**Solución en `resources/views/components/app-layout.blade.php`:** Reemplazar los snippets de GTM y FB por stubs que encolen eventos y carguen los scripts reales al primer `scroll`, `click`, `touchstart` o `keydown`:
```javascript
// Stubs - encolan eventos sin cargar los scripts
window.dataLayer = window.dataLayer || [];
window.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});

!(function (f, b, e, v, n) {
    if (f.fbq) return;
    n = f.fbq = function () {
        n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
    };
    if (!f._fbq) f._fbq = n;
    n.push = n; n.loaded = !0; n.version = "2.0"; n.queue = [];
})(window, document, "script");
fbq("init", "1666700714574473");
fbq("track", "PageView");

// Carga diferida: primera interacción o idle (máx 4s)
(function () {
    var loaded = false;
    function loadTrackers() {
        if (loaded) return;
        loaded = true;
        var g = document.createElement("script");
        g.async = true;
        g.src = "https://www.googletagmanager.com/gtm.js?id=GTM-MFKHNJ9V";
        document.head.appendChild(g);
        var fb = document.createElement("script");
        fb.async = true;
        fb.src = "https://connect.facebook.net/en_US/fbevents.js";
        document.head.appendChild(fb);
        ["scroll", "click", "touchstart", "keydown", "mousemove"].forEach(function (ev) {
            window.removeEventListener(ev, loadTrackers, { passive: true });
        });
    }
    ["scroll", "click", "touchstart", "keydown", "mousemove"].forEach(function (ev) {
        window.addEventListener(ev, loadTrackers, { passive: true, once: true });
    });
    if ("requestIdleCallback" in window) {
        requestIdleCallback(loadTrackers, { timeout: 4000 });
    } else {
        setTimeout(loadTrackers, 4000);
    }
})();
```

### 5. Fuente del sistema vs Instrument Sans (360ms de fuentes)

Se puede eliminar la fuente personalizada y usar la fuente del sistema para eliminar 8 archivos de fuente (~360ms).

**Solución en `resources/css/app.css`:**
```css
@theme {
    --font-sans:
        ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto,
        "Helvetica Neue", Arial, sans-serif,
        "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol",
        "Noto Color Emoji";
}
```

**Solución en `vite.config.js`:** Quitar el plugin `bunny()` y la importación:
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    // ...
});
```

**En layouts:** Quitar `@fonts` de `app-layout.blade.php` y `welcome.blade.php`.

**Después de estos cambios:** ejecutar `npm run build` para regenerar el build sin fuentes.

### 6. Imágenes más grandes que su contenedor (54 KiB)

Los thumbnails de 200px se muestran a ~130px. Se podrían generar thumbnails más pequeños.

**Solución (futuro):** Generar un tamaño intermedio (~150px) en el servicio de optimización de imágenes. No es prioritario.

---

## Resumen de cambios a implementar

| # | Archivo | Cambio | Impacto |
|---|---|---|---|
| 1 | `public/.htaccess` | Agregar cache headers | Cache del navegador de 1 año |
| 2 | `resources/views/components/app-layout.blade.php` | Preloads de FA + diferir GTM/FB | -580ms fuentes, -240ms TBT |
| 3 | `resources/css/app.css` | Fuente del sistema | -360ms fuentes |
| 4 | `vite.config.js` | Quitar plugin bunny/fonts | Sin archivos de fuente |
| 5 | `app-layout.blade.php` + `welcome.blade.php` | Quitar `@fonts` | Sin preloads de fuente |
| 6 | `resources/views/pages/product-detail.blade.php` | Preload LCP + fetchpriority | -3s LCP en detalle |
| 7 | Cloudflare Dashboard | Browser Cache TTL → 1 año | Cache persistente |

**Orden recomendado:** Primero Cloudflare (7), luego `.htaccess` (1), luego FA preloads + deferred trackers (2), luego fuente del sistema (3-5), luego LCP detalle (6). Ejecutar `npm run build` después de los cambios de fuentes.

**No se controla desde aquí:** scripts de FB/GTM (449 KiB), beacon de Cloudflare, AWS capiParamBuilder.
