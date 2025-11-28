@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion

echo.
echo ========================================
echo   Sistema de Gestión Académica SENA
echo   Script de Inicio Local
echo ========================================
echo.

:: Colores (si el terminal los soporta)
set "GREEN=[92m"
set "YELLOW=[93m"
set "RED=[91m"
set "BLUE=[94m"
set "RESET=[0m"

:: Verificar que Docker esté corriendo
echo %BLUE%[1/8] Verificando Docker...%RESET%
docker info >nul 2>&1
if errorlevel 1 (
    echo %RED%❌ ERROR: Docker no está corriendo.%RESET%
    echo.
    echo Por favor:
    echo 1. Abre Docker Desktop
    echo 2. Espera a que termine de iniciar
    echo 3. Ejecuta este script nuevamente
    echo.
    pause
    exit /b 1
)
echo %GREEN%✅ Docker está corriendo%RESET%
echo.

:: Verificar que existe .env
if not exist ".env" (
    echo %YELLOW%⚠️  Archivo .env no encontrado%RESET%
    if exist ".env.example" (
        echo %BLUE%[2/8] Copiando .env.example a .env...%RESET%
        copy /Y .env.example .env >nul
        echo %GREEN%✅ Archivo .env creado%RESET%
    ) else (
        echo %RED%❌ ERROR: No se encontró .env.example%RESET%
        pause
        exit /b 1
    )
) else (
    echo %GREEN%✅ Archivo .env encontrado%RESET%
)
echo.

:: Verificar que existe vendor (dependencias de Composer)
if not exist "vendor" (
    echo %YELLOW%⚠️  Dependencias de Composer no encontradas%RESET%
    echo %BLUE%[3/8] Instalando dependencias de Composer...%RESET%
    echo Esto puede tardar varios minutos...
    
    :: Intentar con Composer local primero
    if exist "composer.phar" (
        php composer.phar install
    ) else (
        :: Intentar con Composer global
        composer install 2>nul
        if errorlevel 1 (
            echo %YELLOW%⚠️  Composer no encontrado localmente, usando Docker...%RESET%
            docker run --rm -v "%cd%:/app" -w /app composer:latest install
        )
    )
    
    if errorlevel 1 (
        echo %RED%❌ ERROR: No se pudieron instalar las dependencias de Composer%RESET%
        echo Por favor instala Composer manualmente: https://getcomposer.org/
        pause
        exit /b 1
    )
    echo %GREEN%✅ Dependencias de Composer instaladas%RESET%
) else (
    echo %GREEN%✅ Dependencias de Composer encontradas%RESET%
)
echo.

:: Detener contenedores existentes (si los hay)
echo %BLUE%[4/8] Verificando contenedores existentes...%RESET%
docker-compose down >nul 2>&1
echo %GREEN%✅ Contenedores verificados%RESET%
echo.

:: Iniciar contenedores Docker
echo %BLUE%[5/8] Iniciando contenedores Docker...%RESET%
echo Esto puede tardar varios minutos la primera vez...
docker-compose up -d

if errorlevel 1 (
    echo %RED%❌ ERROR: No se pudieron iniciar los contenedores%RESET%
    echo Verifica los logs con: docker-compose logs
    pause
    exit /b 1
)

:: Esperar a que los contenedores estén listos
echo %YELLOW%⏳ Esperando a que los servicios estén listos...%RESET%
timeout /t 10 /nobreak >nul

:: Verificar que el contenedor esté corriendo
echo %YELLOW%⏳ Esperando a que el contenedor laravel.test inicie...%RESET%
timeout /t 8 /nobreak >nul

docker-compose ps | findstr "laravel.test" >nul
if errorlevel 1 (
    echo %YELLOW%⚠️  El contenedor laravel.test no está corriendo%RESET%
    echo.
    echo %BLUE%Revisando logs del contenedor...%RESET%
    docker-compose logs laravel.test --tail 20
    echo.
    
    :: Verificar si es el error de line endings
    docker-compose logs laravel.test 2>&1 | findstr "bash" >nul
    if not errorlevel 1 (
        echo %YELLOW%⚠️  Detectado error de line endings (problema común en Windows)%RESET%
        echo.
        echo %BLUE%Intentando usar configuración alternativa...%RESET%
        
        if exist "docker-compose.alt.yml" (
            echo %GREEN%✅ Usando docker-compose.alt.yml (sin Sail)%RESET%
            docker-compose down >nul 2>&1
            docker-compose -f docker-compose.alt.yml up -d
            
            timeout /t 10 /nobreak >nul
            docker-compose -f docker-compose.alt.yml ps | findstr "sena-laravel" >nul
            if not errorlevel 1 (
                echo %GREEN%✅ Contenedor alternativo iniciado correctamente%RESET%
                echo %YELLOW%Nota: La aplicación estará en http://localhost:8000%RESET%
                goto :contenedor_ok
            )
        )
        
        echo %YELLOW%⚠️  Configuración alternativa no disponible o falló%RESET%
        echo %BLUE%Intentando reconstruir la imagen...%RESET%
        echo Esto puede tardar varios minutos...
        echo.
        
        docker-compose down laravel.test >nul 2>&1
        docker-compose build --no-cache laravel.test
        
        if errorlevel 1 (
            echo %RED%❌ ERROR: No se pudo reconstruir la imagen%RESET%
            echo.
            echo %YELLOW%Soluciones alternativas:%RESET%
            echo 1. Revisa SOLUCION_DOCKER_ERROR.md
            echo 2. Usa XAMPP siguiendo GUIA_INSTALACION.md
            echo 3. Ejecuta: docker-compose -f docker-compose.alt.yml up -d
            echo.
            pause
            exit /b 1
        )
        
        echo %GREEN%✅ Imagen reconstruida, iniciando contenedor...%RESET%
        docker-compose up -d laravel.test
        
        timeout /t 8 /nobreak >nul
        docker-compose ps | findstr "laravel.test" >nul
        if errorlevel 1 (
            echo %RED%❌ ERROR: El contenedor sigue sin iniciar%RESET%
            echo.
            echo %YELLOW%⚠️  Docker está teniendo problemas con Laravel Sail en Windows%RESET%
            echo.
            echo %GREEN%💡 Solución Recomendada: Usar XAMPP%RESET%
            echo.
            echo 1. Sigue la guía: GUIA_INSTALACION.md
            echo 2. Usa start.bat para iniciar el servidor
            echo 3. Accede a http://localhost:8000
            echo.
            echo O revisa SOLUCION_DOCKER_ERROR.md para más opciones
            echo.
            pause
            exit /b 1
        )
        :contenedor_ok
    ) else (
        echo %RED%❌ ERROR: El contenedor laravel.test no está corriendo%RESET%
        echo.
        echo %YELLOW%Posibles soluciones:%RESET%
        echo 1. Verifica que Docker Desktop esté corriendo
        echo 2. Revisa los logs: docker-compose logs laravel.test
        echo 3. Verifica que el puerto 80 no esté en uso
        echo 4. Revisa SOLUCION_DOCKER_ERROR.md
        echo.
        pause
        exit /b 1
    )
)

echo %GREEN%✅ Contenedores iniciados correctamente%RESET%
echo.

:: Generar key de aplicación (si no existe)
echo %BLUE%[6/8] Verificando key de aplicación...%RESET%
docker-compose exec -T laravel.test php artisan key:generate --force >nul 2>&1
echo %GREEN%✅ Key de aplicación configurada%RESET%
echo.

:: Ejecutar migraciones
echo %BLUE%[7/8] Ejecutando migraciones de base de datos...%RESET%
docker-compose exec -T laravel.test php artisan migrate --force

if errorlevel 1 (
    echo %YELLOW%⚠️  Advertencia: Hubo un problema con las migraciones%RESET%
    echo Puedes ejecutarlas manualmente después con:
    echo   docker-compose exec laravel.test php artisan migrate
) else (
    echo %GREEN%✅ Migraciones ejecutadas correctamente%RESET%
)
echo.

:: Instalar dependencias de Node.js
echo %BLUE%[8/8] Verificando dependencias de Node.js...%RESET%
if not exist "node_modules" (
    echo Instalando dependencias de Node.js...
    docker-compose exec -T laravel.test npm install
    
    if errorlevel 1 (
        echo %YELLOW%⚠️  Advertencia: Hubo un problema instalando dependencias de Node%RESET%
        echo Puedes instalarlas manualmente después con:
        echo   docker-compose exec laravel.test npm install
    ) else (
        echo %GREEN%✅ Dependencias de Node.js instaladas%RESET%
    )
) else (
    echo %GREEN%✅ Dependencias de Node.js encontradas%RESET%
)
echo.

:: Limpiar caché
echo %BLUE%Limpiando caché de Laravel...%RESET%
docker-compose exec -T laravel.test php artisan cache:clear >nul 2>&1
docker-compose exec -T laravel.test php artisan config:clear >nul 2>&1
docker-compose exec -T laravel.test php artisan view:clear >nul 2>&1
echo %GREEN%✅ Caché limpiada%RESET%
echo.

:: Mostrar información de acceso
echo.
echo ========================================
echo   ✅ ¡Proyecto iniciado correctamente!
echo ========================================
echo.
echo %GREEN%📱 URLs de Acceso:%RESET%
echo.
echo   🌐 Aplicación:     http://localhost
echo   🔧 Vite Dev:       http://localhost:5173
echo   🗄️  MySQL:          localhost:3306
echo   📦 Redis:          localhost:6379
echo.
echo %GREEN%📝 Comandos Útiles:%RESET%
echo.
echo   Ver logs:          docker-compose logs -f
echo   Detener:           docker-compose down
echo   Reiniciar:         docker-compose restart
echo   Acceder al shell:  docker-compose exec laravel.test bash
echo.
echo %GREEN%🎓 Usuarios de Prueba:%RESET%
echo.
echo   Admin:        admin@test.com / password
echo   Coordinador:  coordinator@test.com / password
echo   Instructor:   instructor@test.com / password
echo   Estudiante:   student@test.com / password
echo.
echo ========================================
echo.

:: Preguntar si quiere iniciar Vite
echo %YELLOW%¿Deseas iniciar Vite en modo desarrollo? (S/N):%RESET%
set /p iniciar_vite="> "

if /i "%iniciar_vite%"=="S" (
    echo.
    echo %BLUE%Iniciando Vite en modo desarrollo...%RESET%
    echo %YELLOW%Presiona Ctrl+C para detener Vite%RESET%
    echo.
    docker-compose exec laravel.test npm run dev
) else (
    echo.
    echo %YELLOW%Para iniciar Vite manualmente, ejecuta:%RESET%
    echo   docker-compose exec laravel.test npm run dev
    echo.
    echo %YELLOW%O para compilar assets para producción:%RESET%
    echo   docker-compose exec laravel.test npm run build
    echo.
)

echo.
echo %GREEN%¡Listo! El proyecto está corriendo.%RESET%
echo.
pause

