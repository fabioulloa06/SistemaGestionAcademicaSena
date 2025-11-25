@echo off
setlocal enabledelayedexpansion

REM Batch helper to lift the docker compose stack for this project.
REM It keeps running containers in the background using `docker compose up -d`.

set SCRIPT_DIR=%~dp0
pushd "%SCRIPT_DIR%" >nul

where docker >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker CLI no esta disponible. Instala Docker Desktop y vuelve a intentarlo.
    goto :EOF
)

docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker no esta ejecutandose. Abre Docker Desktop y espera a que inicie.
    goto :EOF
)

set SERVICES=web vite queue scheduler mysql

if /i "%1"=="--rebuild" (
    echo Ejecutando: docker compose build --pull %SERVICES%
    docker compose build --pull %SERVICES%
    if errorlevel 1 goto :EOF
    shift
)

if not "%1"=="" (
    set SERVICES=%*
)

echo Ejecutando: docker compose up -d --build %SERVICES%
docker compose up -d --build %SERVICES%
if errorlevel 1 goto :EOF

echo.
docker compose ps
echo.
echo Servicios activos. Usa "docker compose down" para apagarlos.

popd >nul
endlocal
exit /b 0

