@echo off
chcp 65001 >nul
echo.
echo ========================================
echo   Iniciando Proyecto Local
echo   Sistema de Gestión Académica SENA
echo ========================================
echo.

REM Verificar que existe .env
if not exist ".env" (
    echo [ERROR] El archivo .env no existe.
    echo [INFO] Creando archivo .env desde .env.example...
    if exist ".env.example" (
        copy /Y .env.example .env >nul
        echo [INFO] Archivo .env creado. Ejecuta 'php artisan key:generate' si es necesario.
    ) else (
        echo [ERROR] No se encontró .env.example. Por favor, crea el archivo .env manualmente.
        pause
        exit /b 1
    )
)

REM Verificar que existen las dependencias de Composer
if not exist "vendor\autoload.php" (
    echo [INFO] Las dependencias de Composer no están instaladas.
    echo [INFO] Instalando dependencias de Composer...
    if exist "composer.phar" (
        php composer.phar install
    ) else (
        composer install
    )
    if errorlevel 1 (
        echo [ERROR] Error al instalar dependencias de Composer.
        pause
        exit /b 1
    )
)

REM Verificar que existen las dependencias de npm
if not exist "node_modules" (
    echo [INFO] Las dependencias de npm no están instaladas.
    echo [INFO] Instalando dependencias de npm...
    call npm install
    if errorlevel 1 (
        echo [ERROR] Error al instalar dependencias de npm.
        pause
        exit /b 1
    )
)

echo.
echo [INFO] Iniciando servidores...
echo.

REM Iniciar PHP Artisan Serve en una nueva ventana
echo [INFO] Iniciando servidor Laravel (puerto 8000)...
start "Laravel Server" cmd /k "php artisan serve"

REM Esperar un momento para que el servidor Laravel inicie
timeout /t 3 /nobreak >nul

REM Iniciar Vite en modo desarrollo (para hot-reload)
echo [INFO] Iniciando Vite Dev Server (puerto 5173)...
start "Vite Dev Server" cmd /k "npm run dev"

REM Esperar un poco más para que ambos servidores estén listos
timeout /t 3 /nobreak >nul

echo.
echo ========================================
echo   Servidores Iniciados Exitosamente
echo ========================================
echo.
echo Servidores disponibles:
echo   Laravel: http://localhost:8000
echo   Vite Dev: http://localhost:5173
echo.

REM Abrir el navegador automáticamente
echo [INFO] Abriendo navegador en http://localhost:8000...
timeout /t 2 /nobreak >nul
start http://localhost:8000

echo.
echo ========================================
echo   Sistema listo para usar
echo ========================================
echo.
echo Presiona cualquier tecla para cerrar esta ventana...
echo (Los servidores seguirán corriendo en sus ventanas)
echo.
pause >nul

