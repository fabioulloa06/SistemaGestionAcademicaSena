@echo off
chcp 65001 >nul
echo.
echo ========================================
echo   Configuracion Docker con Laravel Sail
echo   Sistema de Gestion Academica SENA
echo ========================================
echo.

REM Verificar que Docker esta instalado
docker --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta instalado o no esta en el PATH.
    echo [INFO] Por favor instala Docker Desktop desde: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

echo [INFO] Docker encontrado.
echo.

REM Verificar que existe .env
if not exist ".env" (
    echo [INFO] Creando archivo .env desde .env.example...
    if exist ".env.example" (
        copy /Y .env.example .env >nul
        echo [INFO] Archivo .env creado.
    ) else (
        echo [ERROR] No se encontro .env.example.
        pause
        exit /b 1
    )
)

echo [INFO] Configurando .env para Docker/MySQL...
echo.

REM Configurar variables de entorno para Docker
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_HOST=127.0.0.1', 'DB_HOST=mysql' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_PORT=3306', 'DB_PORT=3306' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_DATABASE=laravel', 'DB_DATABASE=sena_db' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_USERNAME=root', 'DB_USERNAME=sail' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '# DB_PASSWORD=', 'DB_PASSWORD=password' | Set-Content .env"

echo [INFO] Variables de entorno configuradas para MySQL.
echo.

REM Verificar que Laravel Sail esta disponible
if not exist "vendor\laravel\sail" (
    echo [INFO] Laravel Sail no esta instalado. Instalando...
    if exist "composer.phar" (
        php composer.phar require laravel/sail --dev
    ) else (
        composer require laravel/sail --dev
    )
    if errorlevel 1 (
        echo [ERROR] Error al instalar Laravel Sail.
        pause
        exit /b 1
    )
)

echo [INFO] Iniciando contenedores Docker...
echo.

REM Iniciar Docker Compose
docker-compose up -d

if errorlevel 1 (
    echo [ERROR] Error al iniciar contenedores Docker.
    pause
    exit /b 1
)

echo.
echo [INFO] Esperando a que los servicios esten listos...
timeout /t 10 /nobreak >nul

echo [INFO] Ejecutando migraciones...
docker-compose exec -T laravel.test php artisan migrate --force

echo [INFO] Ejecutando seeders...
docker-compose exec -T laravel.test php artisan db:seed --force

echo.
echo ========================================
echo   Docker configurado exitosamente
echo ========================================
echo.
echo Servicios disponibles:
echo   Aplicacion: http://localhost
echo   MySQL: localhost:3306
echo   Redis: localhost:6379
echo.
echo Comandos utiles:
echo   Ver logs: docker-compose logs -f
echo   Detener: docker-compose down
echo   Reiniciar: docker-compose restart
echo.
pause


