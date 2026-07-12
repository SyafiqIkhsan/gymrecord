<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (getenv('VERCEL_ENV')) {
    $tmpDir = sys_get_temp_dir();
    $storagePath = $tmpDir . '/laravel-storage';

    // Redirect all Laravel cache / compiled paths to /tmp
    putenv('APP_CONFIG_CACHE=' . $tmpDir . '/config.php');
    putenv('APP_EVENTS_CACHE=' . $tmpDir . '/events.php');
    putenv('APP_PACKAGES_CACHE=' . $tmpDir . '/packages.php');
    putenv('APP_ROUTES_CACHE=' . $tmpDir . '/routes.php');
    putenv('APP_SERVICES_CACHE=' . $tmpDir . '/services.php');
    putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

    // SQLite database di /tmp
    $dbPath = $tmpDir . '/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
    }
    putenv('DB_DATABASE=' . $dbPath);

    // Buat direktori storage di /tmp
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

    // Pastikan storage symlink target exists
    $publicStorage = $storagePath . '/app/public';
    if (!is_dir($publicStorage)) {
        mkdir($publicStorage, 0755, true);
    }
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
