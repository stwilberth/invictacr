# Servidor Contabo — OpenCode + Telegram

Servidor: `157.173.194.183` (vmi3508281.contaboserver.net)
Usuario: `root` · Puerto: `22` · Llave: `~/.ssh/invictacr_prod.pem`

## 1. Conectarse por SSH

```bash
ssh -i ~/.ssh/invictacr_prod.pem -p 22 root@157.173.194.183
```

> Atajo (ya configurado en `~/.ssh/config`): `ssh invictacr-prod`

## 2. Reiniciar OpenCode (aplica cambios de configuración)

```bash
systemctl restart opencode-serve.service && sleep 2 && systemctl status opencode-serve.service --no-pager
```

## Comandos útiles de diagnóstico

```bash
# Ver si el servidor está corriendo
systemctl is-active opencode-serve.service

# Ver últimos errores del servidor
journalctl -u opencode-serve.service --since '5 minutes ago' --no-pager | tail -n 30

# Ver estado de los bots de Telegram
systemctl is-active opencode-telegram.service opencode-telegram-cocoart.service opencode-telegram-puertojimenez.service

# Verificar que el puerto 4096 está escuchando
ss -tlnp | grep 4096

# Validar que todos los modelos tienen limit.context + limit.output
python3 - <<'PYEOF'
import json
d = json.load(open('/root/.config/opencode/opencode.json'))
m = d['provider']['cheaper-inference']['models']
bad = [k for k, v in m.items() if 'output' not in v.get('limit', {}) or 'context' not in v.get('limit', {})]
print('MAL:', bad if bad else 'NINGUNO - todo OK')
print('TOTAL:', len(m))
PYEOF
```

## Archivos importantes en el servidor

| Qué | Ruta |
|---|---|
| Configuración OpenCode | `/root/.config/opencode/opencode.json` |
| Backup bueno (2026-09-23) | `/root/.config/opencode/opencode.json.bak-20260923` |
| Servicio OpenCode | `opencode-serve.service` (puerto 4096) |

## Error típico: `Configuration is invalid ... Missing key ... limit.output`

Pasa cuando se agrega un modelo nuevo sin el campo `limit.output`.
Cada modelo necesita ambos campos:

```json
"mi-modelo-nuevo": {
  "name": "Mi Modelo",
  "limit": { "context": 1048576, "output": 65536 }
}
```

Después de corregirlo, reiniciar con el comando del punto 2.
