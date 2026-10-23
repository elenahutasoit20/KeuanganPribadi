param(
    [string]$DbHost = "127.0.0.1",
    [string]$DbPort = "3306",
    [string]$DbDatabase = "db_keuangan_pribadi",
    [string]$DbUser = "root",
    [string]$DbPass = ""
)

$envPath = Join-Path -Path (Get-Location) -ChildPath ".env"
if (-not (Test-Path $envPath)) {
    Write-Error ".env not found in current directory: $(Get-Location)"
    exit 1
}

$timestamp = Get-Date -Format "yyyyMMddHHmmss"
Copy-Item $envPath "$envPath.bak.$timestamp"

$lines = Get-Content $envPath -ErrorAction Stop

# key => value map
$kv = @{
    'DB_CONNECTION' = 'mysql'
    'DB_HOST'       = $DbHost
    'DB_PORT'       = $DbPort
    'DB_DATABASE'   = $DbDatabase
    'DB_USERNAME'   = $DbUser
    'DB_PASSWORD'   = $DbPass
}

foreach ($k in $kv.Keys) {
    $found = $false
    for ($i = 0; $i -lt $lines.Count; $i++) {
        if ($lines[$i] -match "^\s*" + [regex]::Escape($k) + "\s*=") {
            $lines[$i] = "$k=$($kv[$k])"
            $found = $true
            break
        }
    }
    if (-not $found) {
        $lines += "$k=$($kv[$k])"
    }
}

# Write back
Set-Content -Path $envPath -Value $lines -Encoding UTF8

Write-Host "Updated .env with database settings. Backup created: .env.bak.$timestamp" -ForegroundColor Green
