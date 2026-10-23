<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use the DB facade to read migrations table
$migrations = Illuminate\Support\Facades\DB::table('migrations')->get()->toArray();

print_r($migrations);

