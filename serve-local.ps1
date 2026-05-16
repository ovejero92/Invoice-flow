# Sirve Laravel desde una unidad sustituida (p. ej. P:\) para evitar fallos de PHP en Windows
# cuando la ruta real contiene tildes o caracteres no ASCII.
#
# Uso (PowerShell), desde la carpeta ProjInvoiceFlow:
#   .\serve-local.ps1
#
# Al terminar, libera la unidad:
#   subst P: /d

$ErrorActionPreference = "Stop"
$drive = "P"
$root = $PSScriptRoot

if (-not (Test-Path "${drive}:\")) {
    subst.exe "${drive}:" $root
    Write-Host "Unidad ${drive}: -> $root"
} else {
    Write-Host "La unidad ${drive}: ya existe. Usa otra letra editando este script o ejecuta: subst ${drive}: /d"
    exit 1
}

Set-Location "${drive}:\"
php artisan serve --host=127.0.0.1 --port=8000
