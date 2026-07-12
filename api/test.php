<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'php_version' => PHP_VERSION,
    'vercel' => getenv('VERCEL') ?: 'not set',
    'vercel_env' => getenv('VERCEL_ENV') ?: 'not set',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'not set',
    'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'not set',
    'app_key_set' => getenv('APP_KEY') ? 'yes' : 'no',
    'has_vendor_autoload' => file_exists(__DIR__ . '/../vendor/autoload.php'),
    'tmp_writable' => is_writable(sys_get_temp_dir()),
    'extensions' => get_loaded_extensions(),
]);
