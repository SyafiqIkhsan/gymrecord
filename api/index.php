<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Tangkap fatal error
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        header('Content-Type: text/plain');
        echo "FATAL: {$error['message']} in {$error['file']}:{$error['line']}\n";
    }
});

set_error_handler(function ($severity, $message, $file, $line) {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

try {
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

        foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/testing', 'framework/views', 'logs'] as $dir) {
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
    echo "FILE: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo $e->getTraceAsString() . "\n";
}
