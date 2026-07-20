# 🐛 Bug: Ruta inválida para `UnifiedTimeline`

## Problema

Al ejecutar `php artisan optimize:clear` (o cualquier comando que cargue las rutas), se lanza la siguiente excepción:

```
UnexpectedValueException: Invalid route action: [App\Livewire\Admin\UnifiedTimeline].
```

## Causa raíz

En `routes/web.php` (línea 81) se define la ruta:

```php
Route::get('/timeline', \App\Livewire\Admin\UnifiedTimeline::class)->name('timeline');
```

El problema es doble:

1. **El componente `App\Livewire\Admin\UnifiedTimeline` no existe** — no hay ningún archivo `app/Livewire/Admin/UnifiedTimeline.php` en el proyecto.
2. **Uso incorrecto como route action** — Los componentes Livewire no tienen un método `__invoke()`, por lo que no pueden usarse directamente como acción de ruta al estilo de un controlador invocable.

## ¿Por qué no falla en producción (actualmente)?

Es probable que en producción las rutas estén cacheadas (`php artisan route:cache` o `php artisan optimize`) desde **antes** de que se agregara esta línea. Cuando las rutas están cacheadas, Laravel no vuelve a parsear `web.php`, por lo que nunca evalúa esta ruta inválida. **Sin embargo, el error aparecerá en producción** la próxima vez que se ejecute `php artisan optimize` o `route:cache` durante un despliegue.

## Soluciones posibles

### Opción A: Eliminar la ruta (si no se necesita aún)

Comentar o eliminar la línea en `routes/web.php`:

```php
// Route::get('/timeline', \App\Livewire\Admin\UnifiedTimeline::class)->name('timeline');
```

### Opción B: Crear el componente Livewire como full-page component

1. Crear el componente:
   ```bash
   php artisan make:livewire Admin/UnifiedTimeline
   ```

2. En el componente generado (`app/Livewire/Admin/UnifiedTimeline.php`), asegurarse de que el método `render()` retorne un layout completo:
   ```php
   public function render()
   {
       return view('livewire.admin.unified-timeline')
           ->layout('layouts.admin'); // o el layout que corresponda
   }
   ```

3. La ruta en `web.php` puede mantenerse como está, ya que los full-page Livewire components sí soportan este patrón.

## Acción requerida

Investigar cuál opción aplica y ejecutarla **antes del próximo despliegue**, ya que `php artisan optimize` fallará y bloqueará el deploy.
