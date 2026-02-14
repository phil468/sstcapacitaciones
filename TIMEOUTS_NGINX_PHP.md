# Ajuste de Timeouts para Actualización Masiva

## Problema

Error 504 Gateway Timeout al procesar ~7300 registros en actualización general.

## Solución implementada

✅ **Procesamiento por lotes** (100 registros cada vez) con barra de progreso en frontend.

## Si aún aparece 504 en lotes individuales

### 1. Nginx (servidor producción)

Editar configuración del sitio:

```bash
sudo nano /etc/nginx/sites-available/tu_sitio
```

Agregar dentro del bloque `server {}` o `location ~ \.php$ {}`:

```nginx
# Aumentar timeouts para rutas de actualización
location ~ ^/personal/actualizacion {
    fastcgi_read_timeout 300;
    fastcgi_send_timeout 300;
    proxy_read_timeout 300;
    proxy_send_timeout 300;

    # resto de configuración PHP-FPM
    fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    include fastcgi_params;
}
```

Probar y recargar:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

### 2. PHP-FPM

Editar `/etc/php/8.2/fpm/php.ini`:

```ini
max_execution_time = 300
max_input_time = 300
```

Reiniciar:

```bash
sudo systemctl restart php8.2-fpm
```

### 3. Laravel (opcional)

Si usas HTTP client para la API Nisira, ya tienes timeout configurado en:

```php
// PersonalController.php
Http::timeout(120)->withHeaders([...])->get(...);
```

### 4. Verificar que el caché funcione

El nuevo código guarda los 7300 registros en caché tras el primer fetch. Verifica:

```bash
php artisan cache:clear
# Si usas Redis:
redis-cli FLUSHDB
```

## Monitoreo

Ver logs en tiempo real (producción):

```bash
tail -f /var/log/nginx/error.log
tail -f storage/logs/laravel.log
```

## Alternativa: Queue/Job (futuro)

Para procesos >10 min, considera usar Laravel Queues:

```php
dispatch(new ActualizarPersonalJob());
```

---

**Fecha:** 2025-11-11
**Responsable:** Copilot + Phil468
