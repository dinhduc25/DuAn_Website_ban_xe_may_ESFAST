<?php

namespace App\Core;

class Request
{
    private array $getParams;
    private array $postParams;
    private array $serverParams;
    private array $fileParams;
    private ?array $jsonParams = null;

    public function __construct()
    {
        $this->getParams = $_GET;
        $this->postParams = $_POST;
        $this->serverParams = $_SERVER;
        $this->fileParams = $_FILES;

        // Parse JSON input nếu có
        $contentType = $this->serverParams['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $this->jsonParams = $decoded;
            }
        }
    }

    public function getMethod(): string
    {
        return strtoupper($this->serverParams['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function isAjax(): bool
    {
        return (!empty($this->serverParams['HTTP_X_REQUESTED_WITH']) &&
            strtolower($this->serverParams['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
            (str_contains($this->serverParams['HTTP_ACCEPT'] ?? '', 'application/json'));
    }

    /**
     * Lấy URI hiện tại đã loại bỏ base URL path và query string
     */
    public function getUri(): string
    {
        $rawUri = $this->serverParams['REQUEST_URI'] ?? '/';
        $path = parse_url($rawUri, PHP_URL_PATH) ?? '/';

        // Lấy SCRIPT_NAME hoặc căn chỉnh basePath cho Laragon (ví dụ: /DuAN hoặc /DuAN/public)
        $scriptName = $this->serverParams['SCRIPT_NAME'] ?? '';
        $baseFolder = dirname($scriptName); // e.g. /DuAN/public or /DuAN

        if ($baseFolder !== '/' && $baseFolder !== '\\' && !empty($baseFolder)) {
            $baseFolder = str_replace('\\', '/', $baseFolder);
            if (str_starts_with($path, $baseFolder)) {
                $path = substr($path, strlen($baseFolder));
            }
        }

        // Bỏ trailing slash nếu không phải root
        $path = rtrim($path, '/');
        return empty($path) ? '/' : $path;
    }

    public function all(): array
    {
        if ($this->jsonParams !== null) {
            return array_merge($this->getParams, $this->jsonParams);
        }
        return array_merge($this->getParams, $this->postParams);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->getParams[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        if ($this->jsonParams !== null && isset($this->jsonParams[$key])) {
            return $this->jsonParams[$key];
        }
        return $this->postParams[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        return $all[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        $all = $this->all();
        return isset($all[$key]) && $all[$key] !== '';
    }

    public function file(string $key): ?array
    {
        if (isset($this->fileParams[$key]) && $this->fileParams[$key]['error'] !== UPLOAD_ERR_NO_FILE) {
            return $this->fileParams[$key];
        }
        return null;
    }
}
