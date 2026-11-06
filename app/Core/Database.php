<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Ngăn chặn khởi tạo từ bên ngoài theo mẫu thiết kế Singleton
     */
    private function __construct() {}
    private function __clone() {}

    /**
     * Lấy đối tượng PDO duy nhất trong suốt vòng đời của request
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $configPath = dirname(__DIR__, 2) . '/config/database.php';
            $config = file_exists($configPath) ? require $configPath : [];

            $driver   = $config['driver'] ?? 'mysql';
            $host     = $config['host'] ?? '127.0.0.1';
            $port     = $config['port'] ?? '3306';
            $database = $config['database'] ?? 'esfast_ecommerce';
            $username = $config['username'] ?? 'root';
            $password = $config['password'] ?? '';
            $charset  = $config['charset'] ?? 'utf8mb4';

            $dsn = "{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}";

            $options = $config['options'] ?? [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // Log lỗi và hiển thị thông báo an toàn, không làm lộ mật khẩu
                error_log('Database Connection Error: ' . $e->getMessage());
                die('Lỗi kết nối cơ sở dữ liệu: Vui lòng kiểm tra lại dịch vụ MySQL.');
            }
        }

        return self::$instance;
    }

    /**
     * Bắt đầu một Transaction
     */
    public static function beginTransaction(): bool
    {
        return self::getInstance()->beginTransaction();
    }

    /**
     * Xác nhận Transaction
     */
    public static function commit(): bool
    {
        return self::getInstance()->commit();
    }

    /**
     * Hủy bỏ Transaction
     */
    public static function rollBack(): bool
    {
        if (self::getInstance()->inTransaction()) {
            return self::getInstance()->rollBack();
        }
        return false;
    }

    /**
     * Kiểm tra trạng thái Transaction
     */
    public static function inTransaction(): bool
    {
        return self::getInstance()->inTransaction();
    }
}
