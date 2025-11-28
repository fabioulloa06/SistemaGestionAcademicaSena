@echo off

REM Script para Windows - Facilita el uso de Laravel Sail
REM Uso: sail.bat [comando]

if "%1"=="" (
    echo.
    echo ========================================
    echo   Laravel Sail - Helper para Windows
    echo ========================================
    echo.
    echo Uso: sail.bat [comando]
    echo.
    echo Comandos disponibles:
    echo   up          - Iniciar contenedores
    echo   down        - Detener contenedores
    echo   restart     - Reiniciar contenedores
    echo   logs        - Ver logs
    echo   shell       - Acceder a terminal
    echo   artisan     - Ejecutar comando artisan
    echo   composer    - Ejecutar comando composer
    echo   npm         - Ejecutar comando npm
    echo   mysql       - Acceder a MySQL
    echo.
    echo Ejemplos:
    echo   sail.bat up
    echo   sail.bat artisan migrate
    echo   sail.bat npm install
    echo.
    exit /b
)

if "%1"=="up" (
    vendor\bin\sail up -d
    goto :end
)

if "%1"=="down" (
    vendor\bin\sail down
    goto :end
)

if "%1"=="restart" (
    vendor\bin\sail restart
    goto :end
)

if "%1"=="logs" (
    vendor\bin\sail logs -f
    goto :end
)

if "%1"=="shell" (
    vendor\bin\sail shell
    goto :end
)

if "%1"=="mysql" (
    vendor\bin\sail mysql
    goto :end
)

if "%1"=="artisan" (
    shift
    vendor\bin\sail artisan %*
    goto :end
)

if "%1"=="composer" (
    shift
    vendor\bin\sail composer %*
    goto :end
)

if "%1"=="npm" (
    shift
    vendor\bin\sail npm %*
    goto :end
)

REM Si no coincide con ningún comando, ejecutarlo directamente
vendor\bin\sail %*

:end

