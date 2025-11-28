# Solución al Error: `/usr/bin/env: 'bash\r': No such file or directory`

## Problema
El contenedor `laravel.test` no inicia debido a un problema de line endings (CRLF vs LF) en el script `start-container` de Laravel Sail. Este es un problema común en Windows.

## Solución Recomendada: Usar XAMPP

Si Docker sigue dando problemas, **la solución más rápida es usar XAMPP**:

1. Sigue la guía en `GUIA_INSTALACION.md`
2. Usa `start.bat` para iniciar el servidor
3. Accede a `http://localhost:8000`

Esta es la opción más estable para desarrollo local en Windows.

## Otras Soluciones

### Opción 1: Configurar Git para Line Endings

Usa el archivo `docker-compose.alt.yml` que no depende de Laravel Sail:

```bash
# Detener contenedores actuales
docker-compose down

# Usar el archivo alternativo
docker-compose -f docker-compose.alt.yml up -d
```

**Nota:** Con esta opción, la aplicación estará en `http://localhost:8000` en lugar de `http://localhost:80`.

### Opción 2: Configurar Git para Line Endings

```bash
# Configurar Git para usar LF en lugar de CRLF
git config core.autocrlf false
git config core.eol lf

# Luego reconstruir
docker-compose down
docker-compose build --no-cache laravel.test
docker-compose up -d
```

### Opción 3: Usar Laravel Sail directamente

```bash
# Si tienes Composer instalado
composer install
php artisan sail:install

# Luego usar Sail
vendor\bin\sail up -d
```

### Opción 4: Usar XAMPP (Alternativa sin Docker)

Si Docker sigue dando problemas:
1. Sigue la guía en `GUIA_INSTALACION.md`
2. Usa `start.bat` para iniciar el servidor
3. Accede a `http://localhost:8000`

## Verificación

Después de aplicar cualquier solución, verifica:

```bash
docker-compose ps
# O si usas el alternativo:
docker-compose -f docker-compose.alt.yml ps
```

El contenedor `laravel.test` (o `sena-laravel`) debe aparecer con estado "Up".

## Recomendación

**Para desarrollo rápido:** Usa la Opción 1 (docker-compose.alt.yml) o la Opción 4 (XAMPP).
**Para producción:** Configura Git correctamente (Opción 2) y usa el docker-compose.yml principal.

