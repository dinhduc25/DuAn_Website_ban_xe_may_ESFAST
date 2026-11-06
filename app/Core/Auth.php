<?php

namespace App\Core;

class Auth
{
    private const SESSION_USER_KEY = '_auth_user';

    public static function check(): bool
    {
        return Session::has(self::SESSION_USER_KEY);
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function user(): ?array
    {
        return Session::get(self::SESSION_USER_KEY);
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::check() && self::role() === 'admin';
    }

    public static function isCustomer(): bool
    {
        return self::check() && self::role() === 'customer';
    }

    public static function login(array $user): void
    {
        // Chống session fixation
        Session::regenerate();
        // Không lưu password hash vào Session
        unset($user['password']);
        Session::set(self::SESSION_USER_KEY, $user);
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_USER_KEY);
        Session::destroy();
    }

    /**
     * Bắt buộc phải đăng nhập
     */
    public static function requireAuth(string $redirect = '/login'): void
    {
        if (self::guest()) {
            Session::setFlash('error', 'Vui lòng đăng nhập để tiếp tục.');
            header("Location: $redirect");
            exit;
        }
    }

    /**
     * Bắt buộc phải là quyền Admin
     */
    public static function requireAdmin(): void
    {
        self::requireAuth('/login');

        if (!self::isAdmin()) {
            Session::setFlash('error', 'Bạn không có quyền truy cập khu vực quản trị!');
            header("Location: /");
            exit;
        }
    }
}
