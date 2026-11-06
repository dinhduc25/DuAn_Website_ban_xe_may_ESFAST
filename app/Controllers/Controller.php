<?php

namespace App\Controllers;

use App\Core\Session;
use App\Core\Validator;

abstract class Controller
{
    /**
     * Render View cùng với Layout được chỉ định
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        // Trích xuất biến từ mảng dữ liệu để View có thể truy cập trực tiếp
        extract($data);

        // Biến toàn cục hữu ích cho View
        $csrfToken = Session::token();
        $flashSuccess = Session::getFlash('success');
        $flashError = Session::getFlash('error');
        $flashWarning = Session::getFlash('warning');

        $viewFile = dirname(__DIR__) . '/Views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Không tìm thấy file View: {$viewFile}");
        }

        // Bắt nội dung của View
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Nếu layout là false hoặc rỗng thì render trực tiếp nội dung view
        if ($layout === '' || $layout === false) {
            echo $content;
            return;
        }

        $layoutFile = dirname(__DIR__) . '/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Không tìm thấy file Layout: {$layoutFile}");
        }

        require $layoutFile;
    }

    /**
     * Trả về phản hồi dạng JSON
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Chuyển hướng người dùng đến URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Quay trở lại trang trước đó
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    /**
     * Xác thực dữ liệu đầu vào
     */
    protected function validate(array $data, array $rules): Validator
    {
        return Validator::make($data, $rules);
    }
}
