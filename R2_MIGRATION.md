# Migración de Imágenes a R2 (Cloudflare)

## Credenciales R2

Configurar en `.env` de producción:

```env
AWS_ACCESS_KEY_ID=<tu_access_key>
AWS_SECRET_ACCESS_KEY=<tu_secret_key>
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=invictacr
AWS_ENDPOINT=https://fef68f2ef09a1b432764edcf35b21cc5.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true
```

URL pública del bucket: `https://pub-fef68f2ef09a1b432764edcf35b21cc5.r2.dev`

## Pasos de migración

### 1. Configurar `.env` en producción

Agregar las variables `AWS_*` de arriba al `.env` de producción.

### 2. Instalar dependencias

```bash
composer install
```

### 3. Probar primero (dry-run)

```bash
php artisan images:migrate-r2 --dry-run
```

### 4. Ejecutar migración

```bash
php artisan images:migrate-r2
```

## Qué hace el comando

1. Lee todos los productos con imagen de la DB
2. Si la imagen ya está en R2 (mismo filename), la saltea
3. Descarga la imagen desde la URL actual del sitio
4. La sube a R2 en la ruta `relojes/{filename}`
5. **NO modifica las URLs en la DB** - las imágenes siguen sirviéndose localmente

## Notas

- El comando es idempotente: se puede ejecutar múltiples veces sin duplicar trabajo
- Las imágenes se suben con visibilidad `public`
- Si una descarga falla, se cuenta como error y continúa con la siguiente
- **Las URLs en la DB no se modifican** - el usuario no se ve afectado
- El disk `r2` no tiene URL configurada, solo se usa para upload
