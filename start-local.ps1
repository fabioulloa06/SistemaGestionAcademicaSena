# Script PowerShell para iniciar el proyecto local
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Iniciando Proyecto Local" -ForegroundColor Cyan
Write-Host "  Sistema de Gestion Academica SENA" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Verificar que existe .env
if (-not (Test-Path ".env")) {
    Write-Host "[ERROR] El archivo .env no existe." -ForegroundColor Red
    Write-Host "[INFO] Creando archivo .env desde .env.example..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env" -Force
        Write-Host "[INFO] Archivo .env creado. Ejecuta 'php artisan key:generate' si es necesario." -ForegroundColor Green
    } else {
        Write-Host "[ERROR] No se encontro .env.example. Por favor, crea el archivo .env manualmente." -ForegroundColor Red
        Read-Host "Presiona Enter para continuar"
        exit 1
    }
}

# Verificar que existen las dependencias de Composer
if (-not (Test-Path "vendor\autoload.php")) {
    Write-Host "[INFO] Las dependencias de Composer no estan instaladas." -ForegroundColor Yellow
    Write-Host "[INFO] Instalando dependencias de Composer..." -ForegroundColor Yellow
    if (Test-Path "composer.phar") {
        php composer.phar install
    } else {
        composer install
    }
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[ERROR] Error al instalar dependencias de Composer." -ForegroundColor Red
        Read-Host "Presiona Enter para continuar"
        exit 1
    }
}

# Verificar que existen las dependencias de npm
if (-not (Test-Path "node_modules")) {
    Write-Host "[INFO] Las dependencias de npm no estan instaladas." -ForegroundColor Yellow
    Write-Host "[INFO] Instalando dependencias de npm..." -ForegroundColor Yellow
    npm install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[ERROR] Error al instalar dependencias de npm." -ForegroundColor Red
        Read-Host "Presiona Enter para continuar"
        exit 1
    }
}

Write-Host ""
Write-Host "[INFO] Iniciando servidores..." -ForegroundColor Yellow
Write-Host ""

# Iniciar PHP Artisan Serve en una nueva ventana
Write-Host "[INFO] Iniciando servidor Laravel (puerto 8000)..." -ForegroundColor Green
Start-Process cmd -ArgumentList "/k", "php artisan serve" -WindowStyle Normal

# Esperar un momento para que el servidor Laravel inicie
Start-Sleep -Seconds 3

# Iniciar Vite en modo desarrollo (para hot-reload)
Write-Host "[INFO] Iniciando Vite Dev Server (puerto 5173)..." -ForegroundColor Green
Start-Process cmd -ArgumentList "/k", "npm run dev" -WindowStyle Normal

# Esperar un poco mas para que ambos servidores esten listos
Start-Sleep -Seconds 3

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Servidores Iniciados Exitosamente" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Servidores disponibles:" -ForegroundColor Green
Write-Host "  Laravel: http://localhost:8000" -ForegroundColor White
Write-Host "  Vite Dev: http://localhost:5173" -ForegroundColor White
Write-Host ""

# Abrir el navegador automaticamente
Write-Host "[INFO] Abriendo navegador en http://localhost:8000..." -ForegroundColor Yellow
Start-Sleep -Seconds 2
Start-Process "http://localhost:8000"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Sistema listo para usar" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Los servidores estan corriendo en ventanas separadas." -ForegroundColor Green
Write-Host "Presiona Enter para cerrar esta ventana..." -ForegroundColor Yellow
Read-Host


