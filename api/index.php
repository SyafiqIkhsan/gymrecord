<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (getenv('VERCEL_ENV')) {
    $storagePath = sys_get_temp_dir() . '/laravel-storage';

    $dirs = [
        'app/public',
        'framework/cache/data',
        'framework/sessions',
        'framework/testing',
        'framework/views',
        'logs',
    ];

    foreach ($dirs as $dir) {
        $path = $storagePath . '/' . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    // Override env for SQLite database on Vercel
    $dbPath = sys_get_temp_dir() . '/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
    }
    putenv('DB_DATABASE=' . $dbPath);
}

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

if (isset($storagePath)) {
    $app->useStoragePath($storagePath);
}

$app->handleRequest(Request::capture());
