<?php

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (getenv('VERCEL')) {
    $tmpDir = sys_get_temp_dir();
    $storagePath = $tmpDir . '/laravel-storage';

    putenv('APP_CONFIG_CACHE=' . $tmpDir . '/config.php');
    putenv('APP_EVENTS_CACHE=' . $tmpDir . '/events.php');
    putenv('APP_PACKAGES_CACHE=' . $tmpDir . '/packages.php');
    putenv('APP_ROUTES_CACHE=' . $tmpDir . '/routes.php');
    putenv('APP_SERVICES_CACHE=' . $tmpDir . '/services.php');
    putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');

    $dbPath = $tmpDir . '/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
    }
    putenv('DB_DATABASE=' . $dbPath);

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

    $publicStorage = $storagePath . '/app/public';
    if (!is_dir($publicStorage)) {
        mkdir($publicStorage, 0755, true);
    }
}

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    if (isset($storagePath)) {
        $app->useStoragePath($storagePath);
    }

    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    error_log('Laravel 500: ' . (string) $e);

    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }

    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
