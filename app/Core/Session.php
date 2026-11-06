<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Thiết lập cấu hình bảo mật session cookie
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function regenerate(): void
    {
        self::start();
        session_regenerate_id(true);
    }

    /**
     * Flash messages: lưu thông báo hiển thị một lần duy nhất
     */
    public static function setFlash(string $key, string $message): void
    {
        self::start();
        $_SESSION['_flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        self::start();
        if (isset($_SESSION['_flash'][$key])) {
            $msg = $_SESSION['_flash'][$key];
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }
        return null;
    }

    public static function hasFlash(string $key): bool
    {
        self::start();
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Quản lý CSRF Token chống tấn công giả mạo yêu cầu
     */
    public static function token(): string
    {
        self::start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        self::start();
        if (empty($token) || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
