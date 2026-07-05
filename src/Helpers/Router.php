<?php
declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

class Router
{
    private array $routes = [];
    private string $currentGroupPrefix = '';
    private array $currentGroupMiddleware = [];

    /**
     * Define a route group with a prefix and optional middleware.
     */
    public function group(string $prefix, callable $callback, array $middleware = []): void
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $previousGroupMiddleware = $this->currentGroupMiddleware;

        $this->currentGroupPrefix = $previousGroupPrefix . rtrim($prefix, '/');
        $this->currentGroupMiddleware = array_merge($previousGroupMiddleware, $middleware);

        $callback($this);

        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware;
    }

    /**
     * Add a route.
     */
    public function add(string $method, string $path, string|callable $handler): void
    {
        $path = $this->currentGroupPrefix . '/' . ltrim($path, '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        // Convert {param} to named regex capture groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_\-]+)', $path);
        $pattern = '#^' . $pattern . '$#D';

        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $this->currentGroupMiddleware,
        ];
    }

    public function get(string $path, string|callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string|callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    /**
     * Dispatch the current request.
     */
    public function dispatch(string $requestMethod, string $requestUri): void
    {
        $requestUri = rawurldecode($requestUri);
        $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        $path = $path === '/' ? '/' : rtrim($path, '/');
        $method = strtoupper($requestMethod);
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                // Filter out non-string keys from matches to get parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                http_response_code(200);

                $this->executeHandler($route['handler'], $params, $route['middleware']);
                return;
            }
        }

        // Trigger 404
        $this->trigger404();
    }

    private function executeHandler(string|callable $handler, array $params, array $middleware): void
    {
        // Execute route-specific middleware here if needed
        foreach ($middleware as $m) {
            if (is_callable($m)) {
                $m();
            } elseif (class_exists($m)) {
                $instance = new $m();
                if (method_exists($instance, 'handle')) {
                    $instance->handle();
                }
            }
        }

        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$controllerName, $method] = explode('@', $handler);
            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($controllerClass)) {
                throw new RuntimeException("Controller class {$controllerClass} not found");
            }

            $controller = new $controllerClass();
            if (!method_exists($controller, $method)) {
                throw new RuntimeException("Method {$method} not found in {$controllerClass}");
            }

            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new RuntimeException("Invalid route handler format");
    }

    private function trigger404(): void
    {
        http_response_code(404);
        $controllerClass = "App\\Controllers\\HomeController";
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, 'notFound')) {
                $controller->notFound();
                return;
            }
        }
        echo "404 Not Found";
    }
}
