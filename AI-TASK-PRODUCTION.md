# 🤖 Tarea para la IA de producción

## Contexto

En el entorno local, `php artisan optimize:clear` falla con:

```
UnexpectedValueException: Invalid route action: [App\Livewire\Admin\UnifiedTimeline].
```

La causa es que el componente `UnifiedTimeline.php` y sus dependencias **existen en producción pero nunca se hicieron commit ni push al repositorio**. El entorno local no tiene estos archivos.

## Acción requerida

Hacer `git add`, `git commit` y `git push` de **todos** los archivos que fueron creados en producción y que no están en el repositorio. Como mínimo:

### 1. Verificar qué archivos no están trackeados

```bash
git status
```

### 2. Agregar los archivos faltantes

Estos son los archivos que se sabe que existen en producción pero no en el repo:

- `app/Livewire/Admin/UnifiedTimeline.php`
- `resources/views/livewire/admin/unified-timeline.blade.php`

Y posiblemente estos modelos y servicios (verificar con `git status` si no están trackeados):

- `app/Models/AiTimelineInsight.php`
- `app/Models/ExternalFactor.php`
- `app/Models/FacebookAdReport.php`
- `app/Models/FacebookInsight.php`
- `app/Models/FacebookPost.php`
- `app/Models/GitHubCommit.php`
- `app/Models/GoogleAdsReport.php`
- `app/Models/GoogleAnalyticsReport.php`
- `app/Models/SearchConsoleReport.php`
- `app/Services/TimelineAiService.php`

Y cualquier migración asociada que no esté en el repo.

### 3. Commit y push

```bash
git add -A
git status  # revisar que solo se agregan los archivos correctos
git commit -m "feat: agregar componente UnifiedTimeline y dependencias faltantes"
git push
```

### 4. Verificar

Después del push, ejecutar:

```bash
php artisan optimize:clear
php artisan optimize
```

Para confirmar que todo funciona correctamente en producción.

## Importante

- **No modificar** archivos existentes, solo agregar los que faltan.
- Si hay archivos que no deberían estar en el repo (logs, .env, etc.), **no los agreguen**. Usar `git add` selectivo en vez de `git add -A`.
- Una vez hecho el push, el entorno local podrá hacer `git pull` y el error se resolverá.

## Después de completar esta tarea

Eliminar este archivo (`AI-TASK-PRODUCTION.md`) y el archivo `ISSUE-UNIFIED-TIMELINE.md` ya que habrán cumplido su propósito.
