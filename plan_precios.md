# Plan de Precios — Invicta Costa Rica (versión definitiva)

> Esta versión corrige las anteriores. El cambio fundamental: **variedadescr.com es régimen simplificado**, por lo que su precio **NO incluye IVA** y no genera crédito fiscal. En consecuencia, el costo se trabaja **sin IVA** y el precio de venta **con IVA**.

---

## Diagnóstico de la implementación actual (revisión del código)

Hallazgos que condicionan la aplicabilidad del plan:

1. **Campos existentes en `products`**: `precio_venta` (decimal 10,2), `precio_original` (decimal 10,2 nullable), `descuento` (integer), `bloqueado` (bool), `proximo` (bool), `activo` (bool), `stock` (int). **Faltan** (nueva migración): `precio_costo` y `manual_override`.

2. **Fórmula de precio actual** — `VariedadesSyncService::randomPrice()`:
   ```
   ceil($base * 1.13 / 1000) * 1000
   ```
   Hoy se vende a `precio variedadescr + 13% IVA`, redondeado hacia arriba a ₡1.000. **No** hay descuento del 40%, margen mínimo ni competitividad. Debe sustituirse por `PricingService`.

3. **Lógica antigua ya eliminada**: la variación por último dígito del modelo y el `±₡3.000` ya no existen en el código. La sección 15 se reduce a reemplazar `randomPrice()` por `PricingService`.

4. **Fuente de `P`**: el precio del proveedor viene del campo `precio_venta` de la API de variedadescr (`VariedadesSyncService::execute()`, variable `$priceVal = (int) $item["precio_venta"]`), no de un campo propio. Por tanto `P = $priceVal`.

5. **Conflicto semántico de `precio_original`**: el sincronizador lo escribe con el precio de variedadescr, pero `ProductForm::fetchFromInvicta()` y `Upcoming` lo escriben con el **MSRP de invictawatch.com** (referencia de marketing, no del proveedor). El plan lo define como "precio público de variedadescr". **Unificar**: `precio_original` = referencia del proveedor (variedadescr); el MSRP de invictawatch no debe sobrescribirlo (moverlo a otro campo solo si se requiere conservarlo).

6. **`precio_original` no se muestra en la vista pública**: en el sitio solo se usan `precio_venta` y `descuento` (precio promocional calculado en modelo/vista). El plan no altera esto.

7. **Facturas**: `invoice_items` ya guarda `unit_price` y `subtotal` como **snapshot**; las facturas históricas no cambian al actualizar precios. `InvoiceDetail` edita solo campos de cabecera (cliente, montos, estados, fechas), no las líneas. Ver sección "Facturas".

8. **Comandos existentes**: `stock:sync` (cron cada 6h) ejecuta `VariedadesSyncService`. Existe además `invicta:update-prices` (fija `precio_venta` manual por modelo); debe marcar `manual_override = true` al usarse.

9. **Residuo en admin**: `ProductForm` declara `variedades_increase` y lo muestra en la vista, pero esa columna ya se eliminó de la BD (`2026_07_02_070000_drop_variedades_increase_from_products`). Limpiar al integrar `PricingService`.

---

## 1. Objetivo

Definir y automatizar el cálculo de precios para que:

1. El costo real refleje el descuento del 40% de variedadescr (sin IVA).
2. El precio al cliente siempre sea IVA incluido (13%).
3. Cada producto respete un margen bruto mínimo configurable.
4. Los precios sean competitivos respecto a variedadescr.
5. Se pueda vender más agresivo cuando el margen lo permita.
6. Un cambio del proveedor no deje un producto por debajo del margen mínimo.
7. Los precios queden limpios (múltiplos de ₡1.000).
8. El administrador pueda fijar precios manualmente sin ser sobrescrito.
9. La estrategia sea modificable por configuración, sin tocar el código.

---

## 2. Supuestos de negocio (confirmar con contador)

1. **Invicta está en régimen general** y cobra/declara IVA del 13%.
2. **variedadescr es régimen simplificado**: su precio público es el precio final que paga el cliente (sin IVA desglosado) y **no genera crédito fiscal** para Invicta.
3. El descuento del **40%** aplica sobre el precio público de variedadescr.
4. Puede existir obligación de **autoliquidación** del IVA sobre la compra (Art. 9 del reglamento IVA). Normalmente compensable; es una obligación formal, no un costo adicional neto. **Mantener esto fuera de la lógica de precios hasta que el contador lo confirme.**

> Si algún supuesto cambia (ej. el descuento fuera sobre base sin IVA distinta, o hubiera crédito fiscal), ajustar `config/pricing.php` y la fórmula de costo.

---

## 3. Base monetaria

- **`precio_costo`**: sin IVA (es lo que se paga a variedadescr).
- **`precio_venta` y precio promocional**: con IVA (lo que paga el cliente).
- **`precio_original`**: precio público de variedadescr (sin IVA), usado solo como referencia competitiva. Equivale a `P` y su fuente es `$priceVal` de la API de variedadescr — **no** el MSRP de invictawatch (ver diagnóstico, punto 5).

No mezclar bases. El IVA se aplica **una sola vez** y solo del lado de la venta.

---

## 4. Configuración central

Crear `config/pricing.php` como única fuente de configuración:

```php
return [
    'supplier_discount_percent' => 40,       // costo = 60% del precio del proveedor
    'iva_percent' => 13,                     // IVA aplicado a la venta
    'minimum_margin_percent' => 25,          // margen mínimo sobre ingreso neto
    'competitive_difference_amount' => 0,    // colones, puede ser negativo
    'competitive_difference_percent' => 0,   // % sobre precio_original, puede ser negativo
    'rounding' => 1000,                      // redondeo a múltiplos de ₡1.000
    'promotion_minimum_margin_percent' => 25,// margen mínimo en promociones
];
```

No duplicar estos valores en servicios, comandos ni vistas.

---

## 5. Fórmulas

Sea:

```
P   = precio_original (precio público variedadescr, sin IVA)
D   = supplier_discount_percent (40)
IVA = iva_percent (13)
M   = minimum_margin_percent (25)
```

### 5.1 Costo (sin IVA)

```
precio_costo = P × (1 - D/100)
```

```
P = 100.000  →  precio_costo = 100.000 × 0.60 = 60.000
```

### 5.2 Ingreso neto (sin IVA)

```
ingreso_neto = precio_venta / (1 + IVA/100)
```

### 5.3 Margen (sobre ingreso neto)

```
margen_bruto     = ingreso_neto - precio_costo
margen_bruto_pct = margen_bruto / ingreso_neto
```

### 5.4 Precio mínimo rentable (IVA incluido)

```
precio_minimo = (precio_costo / (1 - M/100)) × (1 + IVA/100)
```

```
P = 100.000, costo = 60.000, M = 25
precio_minimo = (60.000 / 0.75) × 1.13 = 90.400
```

Verificación: `ingreso_neto = 90.400 / 1.13 = 80.000`; `margen = 80.000 - 60.000 = 20.000 = 25%`. ✓

### 5.5 Precio competitivo / objetivo

```
precio_objetivo = P × (1 + competitive_difference_percent/100) + competitive_difference_amount
```

Ambos términos opcionales. Ejemplos:

- `amount = -2000`, `percent = 0`  →  vender ₡2.000 por debajo del proveedor.
- `amount = 0`, `percent = -2`     →  vender 2% por debajo.

> **Defensa (v2, no bloqueante):** validar que `competitive_difference_amount` y `competitive_difference_percent` no produzcan un `precio_objetivo <= 0`. El `max(precio_objetivo, precio_minimo)` ya impide precios negativos, pero conviene rechazar configuraciones absurdas (ej. `amount = -100000`).

### 5.6 Precio base

```
precio_base = max(precio_objetivo, precio_minimo)
```

Nunca por debajo del mínimo rentable.

### 5.7 Redondeo

```
precio_final = ceil(precio_base / rounding) × rounding
```

Después de redondear, **recalcular el margen**:

```
si margen_bruto_pct < M:
    precio_final = precio_final + rounding   (y repetir)
```

El redondeo nunca puede dejar el margen por debajo del mínimo.

---

## 6. Promociones y descuento máximo

El campo `descuento` admite **solo valores enteros (0–100)**. Si el negocio requiere decimales más adelante, evaluarlo como cambio de esquema/configuración, no por defecto.

El campo `descuento` (porcentaje promocional) aplica sobre `precio_venta`:

```
precio_promocional = precio_venta × (1 - descuento/100)
```

El precio promocional también debe respetar el margen mínimo de promoción (`M_prom`):

```
ingreso_neto_prom = precio_promocional / (1 + IVA/100)
margen_prom       = (ingreso_neto_prom - precio_costo) / ingreso_neto_prom

requerido: margen_prom >= promotion_minimum_margin_percent
```

### Descuento máximo permitido

```
precio_promocional_min = (precio_costo / (1 - M_prom/100)) × (1 + IVA/100)

descuento_maximo = (1 - precio_promocional_min / precio_venta) × 100
```

Ejemplo (`P = 100.000`, `precio_venta = 100.000`, `costo = 60.000`):

```
precio_promocional_min = (60.000 / 0.75) × 1.13 = 90.400
descuento_maximo = (1 - 90.400 / 100.000) × 100 = 9.6%
```

- 9% de descuento → ₡91.000 → margen promocional ≥ 25% ✓
- 10% de descuento → ₡90.000 → margen promocional < 25% ✗ (rechazar)

### Comportamiento en el admin

- Mostrar descuento actual, precio promocional y margen promocional resultante.
- Si el descuento rompe el margen mínimo, **mostrar error y no permitir guardar**.
- Si más adelante se requiere vender con pérdida, agregar una bandera explícita (`allow_below_minimum_margin`, por defecto `false`). No implementarla ahora salvo necesidad real.

---

## 7. Campos de la tabla `products`

Agregar mediante migraciones normales y reversibles:

| Campo | Tipo | Descripción |
| --- | --- | --- |
| `precio_costo` | decimal(10,2), nullable | Costo de adquisición (sin IVA) |
| `manual_override` | boolean, default false | Impide que el sincronizador modifique precios |

Mantener: `precio_original` (referencia proveedor), `precio_venta` (venta con IVA), `descuento`.

> `precio_original` NO significa que Invicta haya vendido antes a ese precio; es solo la referencia competitiva del proveedor.

---

## 8. `PricingService` (única fuente de verdad)

Crear un servicio central que calcule todo:

- costo, precio mínimo, precio objetivo, precio base, precio final
- redondeo seguro
- margen y margen pct
- precio promocional, margen promocional, descuento máximo

Debe devolver una estructura con al menos:

```
precio_original
precio_costo
precio_minimo
precio_objetivo
precio_base
precio_final
margen_bruto
margen_bruto_pct
precio_promocional
margen_promocional_pct
descuento_maximo
```

Consumirlo desde: sincronizador, formulario admin (Livewire), `VariedadesSyncService`, tests. Prohibido duplicar fórmulas en Blade o Livewire.

### Precisión monetaria

No usar `float` para dinero. Preferir:
- **BCMath** (`bcadd`, `bcdiv`, `bcsub`, `bcmul`) con escala adecuada, o
- trabajar en **enteros/centavos** internamente.

Evitar `$x * 0.60` y `$x / 1.13` con floats. Normalizar a `decimal(10,2)` antes de guardar.

---

## 9. Sincronizador `invicta:sync-prices`

Opciones: `--dry-run`, `--force`.

Procesar únicamente:

```
activo = 1
bloqueado = 0
manual_override = 0
proximo = 0
```

Excluir automáticamente `bloqueado = 1`, `manual_override = 1`, `proximo = 1`.

### Cálculo durante la sincronización

Para cada producto con precio válido (`P > 0`):

```
precio_costo    = P × (1 - D/100)
precio_original = P
precio_minimo, precio_objetivo, precio_base, precio_final → según PricingService
```

### Idempotencia

Comparar `precio_final` con `precio_venta` actual:

- **Iguales**: no hacer UPDATE, no purgar caché.
- **Diferentes**:
  1. Guardar nuevo `precio_venta`.
  2. Actualizar `precio_costo` y `precio_original`.
  3. Purgar caché solo del producto afectado.

### `--force`

Con `--force`, recalcular también productos con `manual_override = true`. Acción explícita; nunca usarla en cron automático.

> **WARNING:** `--force` recalculará productos con `manual_override = true` y puede sobrescribir un precio fijado manualmente. Mostrar este aviso antes de ejecutar y, en `--dry-run --force`, listar qué productos son overrides.

### `--dry-run`

No modificar la BD. Mostrar por producto:

```
Producto / Modelo
Precio proveedor / Costo / Precio mínimo / Precio objetivo / Precio base / Precio final
Precio actual / Diferencia / Margen
```

### Precios inválidos

Si la API devuelve `P <= 0`, no calcular; loguear y dejar que el flujo existente maneje el producto inválido. Nunca generar `precio_costo = 0` o `precio_venta = 0` por error de API.

---

## 10. Caché

Purgar únicamente los productos cuyo precio cambió. Mantener la estrategia Laravel + Redis + Cloudflare. No purgar todo el catálogo.

---

## 11. `manual_override` y detección automática

- `manual_override = true` → el sincronizador no toca `precio_costo`, `precio_original`, `precio_venta`.
- Si el admin modifica manualmente **solo `precio_original`, `precio_costo` o `precio_venta`** al guardar, activar `manual_override = true` automáticamente (no depender solo del checkbox). Otros campos (ej. `descuento`, título, stock) no activan el override.
- El checkbox debe seguir visible para activar/desactivar manualmente.

---

## 12. Formulario administrativo

- Al cambiar `precio_original`, recalcular en cascada usando `PricingService`.
- Mostrar como información útil: costo sugerido, precio sugerido, margen esperado, precio mínimo.
- El admin puede editar manualmente; si lo hace, activar `manual_override`.

---

## 13. Vista pública

Mostrar:
- `precio_venta` como precio normal (IVA incluido).
- Precio promocional cuando `descuento > 0` (con `precio_venta` tachado y % de descuento).

La vista nunca recalcula costos ni márgenes; todo viene del modelo/servicio.

---

## 14. Integración con `VariedadesSyncService`

Cuando un producto entra con stock positivo y sale de `proximo`, calcular el precio inicial con `PricingService` (respetando `manual_override`). A partir de ahí, `invicta:sync-prices` mantiene las actualizaciones.

---

## 15. Eliminación de lógica antigua

La variación por último dígito del modelo y el `+₡3.000 / -₡3.000` automáticos **ya no existen** en el código. Lo único que queda por eliminar es `VariedadesSyncService::randomPrice()` (fórmula `base * 1.13` redondeada), reemplazándolo por `PricingService`.

La competitividad depende solo de `competitive_difference_amount` y `competitive_difference_percent`.

---

## 16. Ejemplos completos

### Ejemplo A — ₡100.000 (referencia)

```
precio_original    = 100.000
precio_costo       = 100.000 × 0.60 = 60.000
precio_minimo      = (60.000 / 0.75) × 1.13 = 90.400
precio_objetivo    = 100.000
precio_base        = max(100.000, 90.400) = 100.000
precio_final       = 100.000
ingreso_neto       = 100.000 / 1.13 = 88.495,58
margen_bruto_pct   = (88.495,58 - 60.000) / 88.495,58 = 32,2%
descuento_maximo   = 9,6%
```

### Ejemplo B — competitivo agresivo

```
competitive_difference_amount = -2.000
precio_objetivo = 98.000
precio_final    = 98.000
margen          = (86.725,66 - 60.000) / 86.725,66 = 30,8%  ✓
```

### Ejemplo C — competitivo por debajo del mínimo

```
precio_objetivo = 85.000
precio_minimo   = 90.400
precio_base     = 90.400
precio_final    = 91.000   (redondeo hacia arriba)
margen          = (80.530,97 - 60.000) / 80.530,97 = 25,5%  ✓
```

### Ejemplo D — producto de ₡50.000

```
precio_costo  = 30.000
precio_minimo = (30.000 / 0.75) × 1.13 = 45.200 → final 46.000 si fuera el piso
precio_final  = 50.000 (competitivo)
margen        = (44.247,79 - 30.000) / 44.247,79 = 32,2%
descuento_max = (1 - 45.200 / 50.000) × 100 = 9,6%
```

### Ejemplo E — promoción

```
precio_venta = 100.000, costo = 60.000
descuento 9%  → precio_promocional = 91.000 → margen_prom = 25,4% ✓
descuento 10% → precio_promocional = 90.000 → margen_prom = 24,7% ✗ (rechazar)
```

---

## 17. Tests obligatorios

- **Costo**: `100.000 → 60.000`.
- **Margen**: `60.000 / 100.000 → 32,2%` (sobre neto, no 40%).
- **Precio mínimo**: `60.000, 25% → 90.400`.
- **Redondeo**: múltiples escenarios; nunca rompe el margen mínimo.
- **Competencia**: diferencias por monto y por porcentaje.
- **Promociones**: 9% válido, 10% rechazado (ejemplo A).
- **Precisión**: precios con decimales sin errores de punto flotante.
- **Manual override**: exclusión del sincronizador; `--force` sí recalcula.
- **Bloqueado / proximo**: exclusión.
- **Idempotencia**: sin cambios → sin UPDATE, sin caché.
- **Caché**: purga solo cuando cambia el precio.
- **Integración**: `VariedadesSyncService` calcula precio inicial con `PricingService`.

---

## 18. Regla comercial definitiva

Prioridad (orden real del algoritmo):

```
1. Calcular costo real (40% de descuento, sin IVA)
2. Garantizar margen mínimo (25% sobre ingreso neto)
3. Calcular precio competitivo / objetivo (variedadescr ± diferencia configurada)
4. Seleccionar el mayor entre precio objetivo y precio mínimo
5. Redondear hacia arriba a ₡1.000
6. Verificar nuevamente el margen (el redondeo nunca lo rompe)
7. Precio final
```

Nunca: mezclar IVA, aplicar IVA dos veces, usar el último dígito del modelo, usar variaciones arbitrarias, vender (ni en promo) por debajo del margen mínimo.

---

## 19. Tareas de implementación (mapeadas al código actual)

- [ ] Migración reversible: `precio_costo` (decimal 10,2 nullable) y `manual_override` (bool default false) en `products`; `unit_cost` (decimal 12,2 nullable) en `invoice_items`.
- [ ] Crear `config/pricing.php`.
- [ ] Crear `app/Services/PricingService.php` (única fuente de verdad, BCMath).
- [ ] `PricingService`: costo, precio mínimo, objetivo, base, final, redondeo seguro, margen, promoción, `descuento_maximo`.
- [ ] Crear `app/Console/Commands/SyncPrices.php` (`invicta:sync-prices`, `--dry-run`, `--force`).
- [ ] Reemplazar `VariedadesSyncService::randomPrice()` por `PricingService`; `precio_original = P = $priceVal` del proveedor.
- [ ] `Product` model: añadir `precio_costo` y `manual_override` a `$fillable`/`$casts`.
- [ ] `ProductForm` (Livewire): recálculo en cascada con `PricingService`, mostrar costo/margen/precio mínimo, detección automática de `manual_override`; quitar residuo `variedades_increase`.
- [ ] `InvoiceCreate` y `CheckoutController`/`PayPalController`: congelar `unit_cost` en `invoice_items` y calcular `estimated_utility` automáticamente.
- [ ] `UpdatePrices` (`invicta:update-prices`): marcar `manual_override = true` al fijar precio manual.
- [ ] `InvoiceDetail`: mantener edición solo de cabecera (no líneas, no re-lectura de precios).
- [ ] Programar `invicta:sync-prices` en cron (junto a `stock:sync`).
- [ ] Tests en `tests/Unit/PricingServiceTest.php` + `tests/Feature/SyncPricesTest.php`.
- [ ] `--dry-run` y revisión antes de la primera sincronización real (ver sección Despliegue).

---

## 20. Facturas: creación y edición (solo futuras)

Regla firme: **las facturas ya emitidas no se recalculan ni se les cambia el precio.** El precio queda congelado en el snapshot de `invoice_items.unit_price`/`subtotal` y en `invoices.subtotal`/`discount`/`total`.

### Creación (factura nueva)
- Al crear una factura (admin `InvoiceCreate` o web `CheckoutController`/`PayPalController`), el precio por unidad sigue siendo `precio_venta` (con IVA) y se aplica el descuento promocional si `descuento > 0`.
- Para que `estimated_utility` sea automático, **copiar el costo** al crear la línea: añadir columna `unit_cost` (decimal 12,2 nullable) a `invoice_items` y congelar `precio_costo` en el momento de la venta. Utilidad estimada por línea ≈ `(unit_price - unit_cost) * quantity` (ajustada por descuento si aplica).
- Si `precio_costo` es null (producto sin costo cargado), `estimated_utility` se deja manual, como hasta ahora.

### Edición
- Solo se editan facturas **futuras/pendientes**; una factura `facturado`/`entregado` no debe ver alterados sus montos.
- La edición actual (`InvoiceDetail`) no toca las líneas; mantenerlo así. **No** reintroducir re-lectura de `Product::precio_venta` al editar una factura existente.
- No hay migración retroactiva: ninguna factura antigua se recalcula con la nueva fórmula de costo/margen.

### Impacto del plan en facturas
- El cambio de `precio_venta` por sincronización **no** afecta facturas pasadas (snapshot).
- Solo facturas creadas **después** del despliegue llevarán el nuevo precio y el costo congelado.

---

## 21. Despliegue / rollout

> **ATENCIÓN — transición de datos:** la BD actual guarda `precio_original` con el valor **viejo** (proveedor + 13%, por la fórmula `randomPrice` que se elimina). El precio bruto del proveedor (sin IVA) viene de la API (`$priceVal`) y lo repuebla `stock:sync`. Por eso `invicta:sync-prices` **no debe correrse antes** de que `stock:sync` haya corrido al menos una vez con el código nuevo; de lo contrario usaría `precio_original` inflado.

1. Crear la migración (`precio_costo`, `manual_override`, `unit_cost`).
2. Ejecutar `stock:sync` (o esperar al cron de cada 6h) para repoblar `precio_original` con el precio bruto del proveedor y calcular costo/precio con `PricingService`.
3. **Recién entonces** ejecutar `invicta:sync-prices --dry-run` y revisar los cambios propuestos.
4. Si hay precios fijados manualmente que no deben tocarse, marcarlos `manual_override = 1` **antes** de correr el sync real.
5. Programar `invicta:sync-prices` en el cron junto a `stock:sync` (cada 6h, 30 min después, working directory `/var/www/invictacostarica`).
6. `invicta:update-prices` (manual) debe poner `manual_override = true` en los productos afectados.
7. Limpiar el residuo `variedades_increase` del formulario admin (columna ya eliminada de la BD).

---

## 22. Condición para considerar terminado

1. Costo refleja el 40% (sin IVA).
2. Venta siempre con IVA (13%).
3. Precio mínimo garantiza margen ≥ 25% sobre neto.
4. Precio competitivo usa variedadescr como referencia.
5. Nunca se vende (ni en promo) por debajo del margen mínimo.
6. Redondeo nunca rompe el margen.
7. Lógica ±₡3.000 y del último dígito eliminadas.
8. `manual_override`, `bloqueado` y `proximo` funcionan.
9. Sincronización idempotente y caché selectiva.
10. Flujo de stock intacto.
11. Formulario admin usa `PricingService`.
12. Tests en verde.
13. `--dry-run` permite revisar antes de aplicar.
14. Facturas históricas intactas; solo las nuevas congelan costo (`unit_cost`) y calculan utilidad automática.
15. `precio_original` = referencia de variedadescr (el MSRP de invictawatch no lo sobrescribe).

Antes de programar, revisar la implementación actual (`Product`, `VariedadesSyncService`, comandos, migraciones, Livewire, vistas, caché, tests) y adaptar nombres a la arquitectura existente. Presentar un resumen de cambios antes de implementar.
