# Plan de Precios — Invicta Costa Rica

## Objetivo

Definir y automatizar el cálculo de precios para que:

1. Los precios sean **similares a los de variedadescr** (el proveedor), pero **no idénticos**: algunos un poco más baratos y otros un poco más caros.
2. El **descuento del 40%** que nos da variedadescr quede reflejado en el costo.
3. El **IVA del 13%** quede correctamente incluido en el precio final.
4. `precio_original` refleje el precio de lista de variedadescr y **no sea editable manualmente**.

---

## Contexto / reglas de negocio

- **Proveedor**: variedadescr.com (API `productos/stock`). El campo `precio_venta` de la API es el precio de lista público del proveedor. Se asume **IVA incluido**.
- **Descuento**: variedadescr nos da **40%** sobre ese precio → nuestro costo es el **60%**.
- **IVA**: 13% en Costa Rica. El precio final al cliente es **IVA incluido**.
- **Referencia**: `precio_original` = precio de variedadescr (para mostrar "antes/después" y como referencia).

---

## Campos en la tabla `products` (migración)

| Campo | Tipo | Notas |
|---|---|---|
| `precio_costo` | `decimal(10,2)` nullable | Costo de adquisición = variedadescr × 0.60. Solo lo escribe el sincronizador. |
| `precio_anterior` | `decimal(10,2)` nullable | Precio previo. Se muestra tachado solo si es **mayor** que `precio_venta`. |

Reutilizamos campos existentes:

| Campo | Uso nuevo |
|---|---|
| `precio_original` | = precio de variedadescr (lista de referencia). No editable manualmente. |
| `precio_venta` | Precio final al cliente (IVA incluido, con varianza y redondeo). |

> `precio_costo` y `precio_anterior` se crean solo si no existen (`Schema::hasColumn`).

---

## Fórmulas de cálculo

Sea `P` = precio público de variedadescr (de la API).

```
precio_costo    = round(P * 0.60)                 // aplica el 40% de descuento
precio_original = P                                // lista de referencia (variedadescr)
precio_venta    = redondear(P * (1 + aumento))     // similar a variedadescr, con varianza
```

**Redondeo** (para que los precios queden limpios):

```
redondear(x) = ceil(x / 1000) * 1000     // múltiplo de ₡1000 hacia arriba
```

**IVA 13%** (queda incluido en `precio_venta`):

```
ingreso_neto  = precio_venta / 1.13      // lo que queda después de IVA
margen_bruto  = ingreso_neto - precio_costo
```

El 13% ya está embebido en el precio de venta; estas dos fórmulas solo sirven para
auditar margen y nunca debe calcularse el precio *sin* el IVA.

---

## Varianza de precios (`aumento`)

Para que los precios no sean idénticos a los de variedadescr, se aplica un factor
`aumento` por producto:

- **Rango sugerido**: `-8%` a `+12%` (configurable).
- **Determinista por modelo**: se deriva del número de modelo con un hash estable,
  para que el mismo reloj conserve siempre el mismo factor entre sincronizaciones
  (no cambia aleatoriamente en cada corrida).

```php
// Ejemplo conceptual (no usar crc32 en producción; usar hash estable):
$aumento = ((crc32($modelo) % 2001) / 1000 - 10) / 100; // -10% .. +10%
$aumento = clamp($aumento, $min, $max);
```

- Opcional: un **aumento base global** (ej. `0%`) que se suma al factor por modelo,
  guardado como configuración para poder subir/bajar todo el catálogo de golpe.

Esto produce: algunos relojes **más baratos**, otros **más caros** que variedadescr,
pero todos dentro de un rango controlado.

---

## Sincronizador de precios

Nueva sección/comando (ej. `invicta:sync-prices`) que:

1. Toma los productos **activos y no bloqueados** (`activo = 1` y `bloqueado = 0`).
2. Calcula `precio_costo`, `precio_original` y `precio_venta` con las fórmulas de arriba.
3. **No toca** productos `bloqueado = 1` (propios).
4. Solo modifica `precio_original` vía el valor `aumento` (nunca manual).
5. Antes de cambiar `precio_venta`, mueve el valor actual a `precio_anterior`
   (para poder mostrar el "antes" tachado).
6. Purga caché (Laravel + Redis + Cloudflare) de los productos afectados.

También se integra al **sync de stock existente** (`VariedadesSyncService`): cuando
un reloj entra con stock positivo, recalcula `precio_costo`/`precio_original`/`precio_venta`.

---

## Reglas de edición manual

- `precio_original`: editable manualmente en el admin; el sincronizador puede sobreescribirlo si se especifica (configurable).
- `precio_costo`: editable manualmente (permite ajustar costos de adquisición si es necesario).
- `precio_venta`: editable manualmente (override puntual).
- `precio_anterior`: se setea automáticamente por el sincronizador al cambiar `precio_venta`; también editable manualmente si se desea forzar un "antes".

Notas:
- Habilitar campos editables requiere actualizar el formulario/admin y respetar señales del sincronizador: si un valor es editado manualmente, el sincronizador no debe sobrescribirlo a menos que se use una opción `--force` o exista una configuración para permitir actualizaciones automáticas.
- Añadir un flag `manual_override` (boolean) por producto para evitar sobrescrituras automáticas cuando se edita manualmente.

---

## Mostrar al cliente

En `resources/views/pages/product-detail.blade.php` (y tarjetas si aplica):

- Precio actual en grande: `precio_venta` (IVA incluido).
- Si `precio_anterior > precio_venta`: mostrar `precio_anterior` **tachado** junto al
  precio actual (indica bajada de precio).
- Si `descuento > 0`: aplicar `precio_venta * (1 - descuento/100)` como precio final
  y mostrar el % de descuento (comportamiento actual).

---

## Ejemplo numérico

Reloj con `P = 100.000` (variedadescr):

```
precio_costo    = 100.000 * 0.60 = 60.000
precio_original = 100.000
aumento         = +5%  →  100.000 * 1.05 = 105.000  →  redondear → 105.000
aumento         = -8%  →  100.000 * 0.92 =  92.000  →  redondear →  92.000

ingreso_neto (aumento +5%) = 105.000 / 1.13 = 92.920
margen_bruto               = 92.920 - 60.000 = 32.920  (~32.9%)
```

---

## Tareas pendientes

- [ ] Migración: agregar `precio_costo` y `precio_anterior`.
- [ ] Config: rango de `aumento` (min/max) y `aumento` base global.
- [ ] Comando `invicta:sync-prices` (con `--dry-run`).
- [ ] Integrar recálculo en `VariedadesSyncService` (stock positivo).
- [ ] Bloquear edición de `precio_original`/`precio_costo` en el admin.
- [ ] Mostrar `precio_anterior` tachado en product-detail.
- [ ] Tests del cálculo (fórmulas + redondeo + varianza determinista).
