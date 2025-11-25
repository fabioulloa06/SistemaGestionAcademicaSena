@echo off
echo ========================================
echo   CONFIGURANDO .env PARA DOCKER
echo ========================================
echo.

REM Verificar si existe .env
if not exist ".env" (
    echo [ERROR] No se encontro el archivo .env
    echo Copiando desde .env.example...
    copy .env.example .env
)

echo Actualizando configuracion de base de datos...

REM Crear archivo temporal con las nuevas configuraciones
powershell -Command "(Get-Content .env) -replace '^DB_CONNECTION=.*', 'DB_CONNECTION=mysql' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) -replace '^DB_HOST=.*', 'DB_HOST=mysql' | Set-Content .env"
del .env.tmp

powershell -Command "(Get-Content .env) -replace '^DB_PORT=.*', 'DB_PORT=3306' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) -replace '^DB_DATABASE=.*', 'DB_DATABASE=sena_db' | Set-Content .env"
del .env.tmp

powershell -Command "(Get-Content .env) -replace '^DB_USERNAME=.*', 'DB_USERNAME=sena' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) -replace '^DB_PASSWORD=.*', 'DB_PASSWORD=secret' | Set-Content .env"
del .env.tmp

powershell -Command "(Get-Content .env) -replace '^APP_URL=.*', 'APP_URL=http://localhost:8080' | Set-Content .env.tmp"
powershell -Command "(Get-Content .env.tmp) | Set-Content .env"
del .env.tmp

echo.
echo [OK] Configuracion actualizada!
echo.
echo Configuracion de base de datos:
echo   DB_CONNECTION=mysql
echo   DB_HOST=mysql
echo   DB_PORT=3306
echo   DB_DATABASE=sena_db
echo   DB_USERNAME=sena
echo   DB_PASSWORD=secret
echo   APP_URL=http://localhost:8080
echo.
echo Ahora ejecuta:
echo   docker compose exec app php artisan config:clear
echo   docker compose exec app php artisan migrate:fresh --seed
echo.
pause

