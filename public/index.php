<?php

declare(strict_types=1);

/**
 * ESFAST ECOMMERCE - Entry Point
 * Tinh hoa xe máy điện thông minh Việt Nam
 */

define('ESFAST_START', microtime(true));
define('ROOT_PATH', dirname(__DIR__));

// Khởi động autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = ROOT_PATH . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Nạp vendor nếu có composer
if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
}

// Đọc file .env
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (str_contains($line, '=')) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            putenv("{$key}={$val}");
            $_ENV[$key] = $val;
            $_SERVER[$key] = $val;
        }
    }
}

use App\Core\Request;
use App\Core\Router;
use App\Core\Session;

// Khởi tạo Session
Session::start();

// Nạp Route
require_once ROOT_PATH . '/routes/web.php';

// Tiếp nhận Request và Dispatch
$request = new Request();
Router::dispatch($request);
