# Métodos de Actualización de Personal

## Problema resuelto
Tras implementar actualización por lotes para evitar 504 Gateway Timeout, surgieron dos inconvenientes:
1. **Command programado** fallaba (esperaba 0 argumentos, recibía Request)
2. **Historial** mostraba "Error" aunque la actualización fue exitosa (estructura de datos incompatible)

## Solución implementada

### 1. Dos métodos separados

#### `actualizacionGeneralCompleta(Request $request)` - Para interfaz web
- **Uso**: Actualización manual desde navegador
- **Características**:
  - Procesa por lotes (100 registros/vez)
  - Barra de progreso en tiempo real
  - Usa caché para evitar múltiples API calls
  - Al finalizar: registra con estructura compatible con historial

#### `actualizacionGeneralSincrona()` - Para command/cron
- **Uso**: Tarea programada (cron)
- **Características**:
  - Procesa todos los registros de una vez
  - No requiere Request
  - Registra con estructura completa (resultado_actualizacion + resultado_estados)
  - Compatible con logs de command

### 2. Registro unificado en historial
Ambos métodos ahora guardan con la misma estructura:
```json
{
  "tipo": "general",
  "resultado_actualizacion": {
    "res": true,
    "message": "..."
  },
  "resultado_estados": {
    "res": true,
    "message": "..."
  },
  "ejecutado_por": 1,
  "ejecutado_por_nombre": "John Deivis",
  "ejecutado_por_sistema": false
}
```

## Uso

### Desde interfaz web
- Usuario hace clic en "Actualización General"
- Se ejecuta `actualizacionGeneralCompleta()` por lotes
- Progreso visible en modal
- Al finalizar: badge verde "Actualización de Personal: Éxito"

### Desde cron (programado)
```bash
# En producción
php artisan personal:actualizar-general

# O vía crontab
0 9 * * * cd /var/www/sstcapacitaciones && php artisan personal:actualizar-general >> /dev/null 2>&1
```

## Archivos modificados
1. `app/Http/Controllers/PersonalController.php`
   - Nuevo método: `actualizacionGeneralSincrona()`
   - Método `actualizacionGeneralCompleta()` ahora registra correctamente
2. `app/Console/Commands/ActualizarPersonalGeneral.php`
   - Llama a `actualizacionGeneralSincrona()` en lugar de `actualizacionGeneralCompleta()`

## Testing

### Probar command local
```bash
php artisan personal:actualizar-general
```

### Verificar historial
1. Ejecutar actualización (web o command)
2. Ir a `/personal/historial-actualizaciones`
3. Debe mostrar badge verde "Actualización de Personal: Éxito"

---
**Fecha:** 2025-11-12  
**Responsable:** Copilot + Phil468
