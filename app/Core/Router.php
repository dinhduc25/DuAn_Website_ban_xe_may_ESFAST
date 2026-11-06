<?php

namespace App\Core;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, string|array $action, array $middlewares = []): void
    {
        self::addRoute('GET', $uri, $action, $middlewares);
    }

    public static function post(string $uri, string|array $action, array $middlewares = []): void
    {
        self::addRoute('POST', $uri, $action, $middlewares);
    }

    private static function addRoute(string $method, string $uri, string|array $action, array $middlewares): void
    {
        $uri = '/' . trim($uri, '/');
        self::$routes[] = [
            'method'      => $method,
            'uri'         => $uri,
            'action'      => $action,
            'middlewares' => $middlewares,
        ];
    }

    public static function dispatch(Request $request): void
    {
        $requestMethod = $request->getMethod();
        $requestUri = '/' . trim($request->getUri(), '/');

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Chuyển uri dạng /products/{slug} hoặc /orders/{id} thành regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
            $pattern = "#^{$pattern}$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Loại bỏ match toàn bộ chuỗi

                // Thực thi Middlewares
                foreach ($route['middlewares'] as $mw) {
                    self::handleMiddleware($mw, $request);
                }

                // Thực thi Controller Action
                self::invokeAction($route['action'], $matches, $request);
                return;
            }
        }

        // Không tìm thấy Route -> 404
        self::handleNotFound();
    }

    private static function handleMiddleware(string $middleware, Request $request): void
    {
        switch ($middleware) {
            case 'auth':
                Auth::requireAuth();
                break;

            case 'admin':
                Auth::requireAdmin();
                break;

            case 'csrf':
                if ($request->isPost()) {
                    $token = $request->post('_csrf_token') ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
                    if (!Session::verifyCsrfToken($token)) {
                        http_response_code(419);
                        if ($request->isAjax()) {
                            header('Content-Type: application/json');
                            echo json_encode(['error' => 'Phiên làm việc hoặc CSRF Token đã hết hạn. Vui lòng tải lại trang.']);
                        } else {
                            Session::setFlash('error', 'Phiên làm việc đã hết hạn. Vui lòng thử lại.');
                            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                        }
                        exit;
                    }
                }
                break;
        }
    }

    private static function invokeAction(string|array $action, array $params, Request $request): void
    {
        if (is_array($action)) {
            $controllerClass = $action[0];
            $methodName = $action[1];
        } elseif (is_string($action) && str_contains($action, '@')) {
            list($controller, $methodName) = explode('@', $action);
            $controllerClass = str_starts_with($controller, 'App\\Controllers\\')
                ? $controller
                : 'App\\Controllers\\' . $controller;
        } else {
            throw new \RuntimeException("Định dạng Action Route không hợp lệ.");
        }

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller {$controllerClass} không tồn tại.");
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $methodName)) {
            throw new \RuntimeException("Phương thức {$methodName} không tồn tại trong {$controllerClass}.");
        }

        // Truyền $request và các tham số url vào action
        call_user_func_array([$controllerInstance, $methodName], array_merge([$request], $params));
    }

    private static function handleNotFound(): void
    {
        http_response_code(404);
        $notFoundView = dirname(__DIR__, 2) . '/app/Views/layouts/404.php';
        if (file_exists($notFoundView)) {
            require $notFoundView;
        } else {
            echo "<h1>404 - Không tìm thấy trang</h1>";
            echo "<p>Trang bạn yêu cầu không tồn tại hoặc đã được chuyển hướng.</p>";
            echo "<a href='/'>Quay về Trang chủ</a>";
        }
        exit;
    }
}
