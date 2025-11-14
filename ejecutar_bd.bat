@echo off
echo ========================================
echo  EJECUTANDO SCRIPT DE BASE DE DATOS
echo ========================================
echo.

REM Verificar si MySQL esta corriendo
echo Verificando conexion a MySQL...
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SELECT 'OK' AS estado;" >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo ERROR: MySQL no esta corriendo!
    echo.
    echo Por favor:
    echo 1. Abre XAMPP Control Panel
    echo 2. Inicia MySQL (boton Start)
    echo 3. Espera a que aparezca en verde
    echo 4. Ejecuta este script nuevamente
    echo.
    pause
    exit /b 1
)

echo MySQL esta corriendo!
echo.
echo Ejecutando script sena_database.sql...
echo Por favor espera, esto puede tomar unos segundos...
echo.

"C:\xampp\mysql\bin\mysql.exe" -u root < sena_database.sql

if %errorlevel% equ 0 (
    echo.
    echo ========================================
    echo  BASE DE DATOS CREADA EXITOSAMENTE!
    echo ========================================
    echo.
    echo La base de datos "sena_db" ha sido creada con:
    echo - 24 tablas
    echo - 8 vistas SQL
    echo - 2 procedimientos almacenados
    echo - Datos iniciales
    echo.
) else (
    echo.
    echo ERROR al ejecutar el script.
    echo Revisa los mensajes de error arriba.
    echo.
)

pause

