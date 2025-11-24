@echo off
echo ===================================================
echo    INICIANDO SISTEMA DE CONTROL DE ASISTENCIAS
echo ===================================================

:: 1. Iniciar Servidor de Desarrollo Laravel (en una nueva ventana)
echo [INFO] Iniciando servidor Laravel (php artisan serve)...
start "Laravel Server" cmd /k "php artisan serve"

:: 2. Iniciar Vite (en una nueva ventana)
echo [INFO] Iniciando Vite (npm run dev)...
start "Vite Assets" cmd /k "npm run dev"

:: 3. Esperar unos segundos y abrir el navegador
echo [INFO] Abriendo el navegador...
timeout /t 5 >nul
start http://127.0.0.1:8000

echo ===================================================
echo    SISTEMA INICIADO EXITOSAMENTE
echo ===================================================
pause
