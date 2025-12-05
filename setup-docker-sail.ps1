# Script PowerShell para configurar Docker con Laravel Sail
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Configuracion Docker con Laravel Sail" -ForegroundColor Cyan
Write-Host "  Sistema de Gestion Academica SENA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar que Docker esta instalado
try {
    $dockerVersion = docker --version 2>&1
    Write-Host "[INFO] Docker encontrado: $dockerVersion" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Docker no esta instalado o no esta en el PATH." -ForegroundColor Red
    Write-Host "[INFO] Por favor instala Docker Desktop desde: https://www.docker.com/products/docker-desktop" -ForegroundColor Yellow
    Read-Host "Presiona Enter para continuar"
    exit 1
}

Write-Host ""

# Verificar que existe .env
if (-not (Test-Path ".env")) {
    Write-Host "[INFO] Creando archivo .env desde .env.example..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env" -Force
        Write-Host "[INFO] Archivo .env creado." -ForegroundColor Green
    } else {
        Write-Host "[ERROR] No se encontro .env.example." -ForegroundColor Red
        Read-Host "Presiona Enter para continuar"
        exit 1
    }
}

Write-Host "[INFO] Configurando .env para Docker/MySQL..." -ForegroundColor Yellow
Write-Host ""

# Configurar variables de entorno para Docker
$envContent = Get-Content .env -Raw
$envContent = $envContent -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql'
$envContent = $envContent -replace '# DB_HOST=127.0.0.1', 'DB_HOST=mysql'
$envContent = $envContent -replace '# DB_PORT=3306', 'DB_PORT=3306'
$envContent = $envContent -replace '# DB_DATABASE=laravel', 'DB_DATABASE=sena_db'
$envContent = $envContent -replace '# DB_USERNAME=root', 'DB_USERNAME=sail'
$envContent = $envContent -replace '# DB_PASSWORD=', 'DB_PASSWORD=password'
Set-Content .env -Value $envContent -NoNewline

Write-Host "[INFO] Variables de entorno configuradas para MySQL." -ForegroundColor Green
Write-Host ""

# Verificar que Laravel Sail esta disponible
if (-not (Test-Path "vendor\laravel\sail")) {
    Write-Host "[INFO] Laravel Sail no esta instalado. Instalando..." -ForegroundColor Yellow
    if (Test-Path "composer.phar") {
        php composer.phar require laravel/sail --dev
    } else {
        composer require laravel/sail --dev
    }
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[ERROR] Error al instalar Laravel Sail." -ForegroundColor Red
        Read-Host "Presiona Enter para continuar"
        exit 1
    }
}

Write-Host "[INFO] Iniciando contenedores Docker..." -ForegroundColor Yellow
Write-Host ""

# Iniciar Docker Compose
docker-compose up -d

if ($LASTEXITCODE -ne 0) {
    Write-Host "[ERROR] Error al iniciar contenedores Docker." -ForegroundColor Red
    Read-Host "Presiona Enter para continuar"
    exit 1
}

Write-Host ""
Write-Host "[INFO] Esperando a que los servicios esten listos..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

Write-Host "[INFO] Ejecutando migraciones..." -ForegroundColor Yellow
docker-compose exec -T laravel.test php artisan migrate --force

Write-Host "[INFO] Ejecutando seeders..." -ForegroundColor Yellow
docker-compose exec -T laravel.test php artisan db:seed --force

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Docker configurado exitosamente" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Servicios disponibles:" -ForegroundColor Green
Write-Host "  Aplicacion: http://localhost" -ForegroundColor White
Write-Host "  MySQL: localhost:3306" -ForegroundColor White
Write-Host "  Redis: localhost:6379" -ForegroundColor White
Write-Host ""
Write-Host "Comandos utiles:" -ForegroundColor Yellow
Write-Host "  Ver logs: docker-compose logs -f" -ForegroundColor White
Write-Host "  Detener: docker-compose down" -ForegroundColor White
Write-Host "  Reiniciar: docker-compose restart" -ForegroundColor White
Write-Host ""
Read-Host "Presiona Enter para continuar"


