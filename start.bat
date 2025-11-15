@echo off
echo Iniciando servidor Laravel y Vite...
echo.

REM Iniciar PHP Artisan Serve en una nueva ventana
start "Laravel Server" cmd /k "php artisan serve"

REM Esperar un momento para que el servidor Laravel inicie
timeout /t 2 /nobreak >nul

REM Iniciar Vite en una nueva ventana
start "Vite Dev Server" cmd /k "npm run dev"

echo.
echo Servidores iniciados:
echo - Laravel: http://localhost:8000
echo - Vite: http://localhost:5173
echo.
echo Presiona cualquier tecla para cerrar esta ventana...
pause >nul

