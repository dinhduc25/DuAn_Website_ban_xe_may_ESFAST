<?php

// Database Initialization Script for ESFAST
require_once __DIR__ . '/../app/Core/Database.php';

use App\Core\Database;

echo "=== KHỞI TẠO CƠ SỞ DỮ LIỆU ESFAST ECOMMERCE ===" . PHP_EOL;

try {
    // 1. Tạo Database nếu chưa có
    $envFile = __DIR__ . '/../.env';
    $host = '127.0.0.1';
    $port = '3306';
    $user = 'root';
    $pass = '';
    $dbname = 'esfast_ecommerce';

    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) continue;
            if (str_contains($line, '=')) {
                list($key, $val) = explode('=', $line, 2);
                $key = trim($key);
                $val = trim($val, " \t\n\r\0\x0B\"'");
                if ($key === 'DB_HOST') $host = $val;
                if ($key === 'DB_PORT') $port = $val;
                if ($key === 'DB_USERNAME') $user = $val;
                if ($key === 'DB_PASSWORD') $pass = $val;
                if ($key === 'DB_DATABASE') $dbname = $val;
            }
        }
    }

    echo "1. Đang kết nối MySQL tại $host:$port..." . PHP_EOL;
    $rawPdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "2. Đang tạo cơ sở dữ liệu `$dbname` (nếu chưa có)..." . PHP_EOL;
    $rawPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $rawPdo->exec("USE `$dbname`;");

    echo "3. Đang thực thi `database/schema.sql`..." . PHP_EOL;
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    $rawPdo->exec($schemaSql);
    echo "   -> Tạo các bảng thành công!" . PHP_EOL;

    echo "4. Đang thực thi `database/seed.sql`..." . PHP_EOL;
    $seedSql = file_get_contents(__DIR__ . '/seed.sql');
    $rawPdo->exec($seedSql);
    echo "   -> Seed dữ liệu mẫu thành công!" . PHP_EOL;

    echo "=== HOÀN TẤT THÀNH CÔNG! ===" . PHP_EOL;
    echo "Tài khoản Demo Admin: admin@esfast.vn / Admin@123" . PHP_EOL;
    echo "Tài khoản Demo Khách: customer@esfast.vn / Customer@123" . PHP_EOL;

} catch (PDOException $e) {
    echo "LỖI KHỞI TẠO DATABASE: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
