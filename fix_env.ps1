# Script para configurar .env para Docker

$envFile = ".env"

# Leer contenido actual
$content = Get-Content $envFile -Raw

# Reemplazar o agregar DB_CONNECTION
if ($content -match "DB_CONNECTION=") {
    $content = $content -replace "DB_CONNECTION=.*", "DB_CONNECTION=mysql"
} else {
    $content = "DB_CONNECTION=mysql`n" + $content
}

# Reemplazar o agregar DB_PORT
if ($content -match "DB_PORT=") {
    $content = $content -replace "DB_PORT=.*", "DB_PORT=3306"
} else {
    # Agregar después de DB_HOST
    $content = $content -replace "(DB_HOST=.*)", "`$1`nDB_PORT=3306"
}

# Guardar
$content | Set-Content $envFile -NoNewline

Write-Host "Configuracion actualizada:" -ForegroundColor Green
Write-Host "  DB_CONNECTION=mysql"
Write-Host "  DB_HOST=mysql"
Write-Host "  DB_PORT=3306"
Write-Host "  DB_DATABASE=sena_db"
Write-Host "  DB_USERNAME=sena"
Write-Host "  DB_PASSWORD=secret"

