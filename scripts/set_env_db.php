<?php
// Usage: php scripts/set_env_db.php [host] [port] [database] [username] [password]
$path = __DIR__ . '/../.env';
if (!file_exists($path)) {
    echo ".env not found at: $path\n";
    exit(1);
}

$backup = $path . '.bak.' . date('YmdHis');
copy($path, $backup);

$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$host = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? '3306';
$db   = $argv[3] ?? 'db_keuangan_pribadi';
$user = $argv[4] ?? 'root';
$pass = $argv[5] ?? '';

$map = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => $host,
    'DB_PORT' => $port,
    'DB_DATABASE' => $db,
    'DB_USERNAME' => $user,
    'DB_PASSWORD' => $pass,
];

foreach ($map as $k => $v) {
    $found = false;
    foreach ($lines as $i => $line) {
        if (preg_match('/^\s*' . preg_quote($k, '/') . '\s*=/', $line)) {
            $lines[$i] = "$k=$v";
            $found = true;
            break;
        }
    }
    if (! $found) {
        $lines[] = "$k=$v";
    }
}

file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL);

echo "Updated .env (backup: $backup)\n";
