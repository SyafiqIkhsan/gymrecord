<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

echo 'VERCEL=' . var_export(getenv('VERCEL'), true) . "\n";
echo 'VERCEL_ENV=' . var_export(getenv('VERCEL_ENV'), true) . "\n";
echo 'PHP_VERSION=' . PHP_VERSION . "\n";
echo 'HAS_VENDOR=' . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'yes' : 'no') . "\n";
echo 'DOC_ROOT=' . ($_SERVER['DOCUMENT_ROOT'] ?? 'none') . "\n";
