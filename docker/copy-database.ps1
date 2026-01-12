# Powershell helper: copy database.sql into docker init folder and start compose
param(
    [string]$SourceSql = "database.sql"
)

if (Test-Path $SourceSql) {
    Copy-Item -Path $SourceSql -Destination "docker/db/init/" -Force
    Write-Host "Copied $SourceSql to docker/db/init/"
} else {
    Write-Host "$SourceSql not found in project root. Skipping copy."
}

Write-Host "Starting docker compose..."
docker compose up -d --build
