<?php
namespace App\Core;

class Router
{
    private Request $request;
    private Response $response;
    private array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $path, $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    public function any(string $path, $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        // Normalize path
        $path = '/' . trim($path, '/');
        if ($path === '//') $path = '/';

        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        // Check routes for current method
        $routesForMethod = $this->routes[$method] ?? [];

        foreach ($routesForMethod as $route) {
            $pattern = $this->convertPathToRegex($route['path']);
            if (preg_match($pattern, $path, $matches)) {
                // Filter out numerical keys from matches to get only named params
                $params = array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                
                $this->dispatch($route['handler'], $params);
                return;
            }
        }

        // If no route matched, dispatch 404
        $this->dispatchNotFound();
    }

    private function convertPathToRegex(string $path): string
    {
        // Escape special chars except { and }
        $pattern = preg_quote($path, '#');

        // Convert {param} placeholders to named regex groups
        $pattern = preg_replace('#\\\{([a-zA-Z0-9_]+)\\\}#', '(?P<$1>[^/]+)', $pattern);

        return '#^' . $pattern . '$#';
    }

    private function dispatch($handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, array_merge([$this->request, $this->response], $params));
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $action] = $handler;

            if (class_exists($class)) {
                $controller = new $class($this->request, $this->response);
                if (method_exists($controller, $action)) {
                    call_user_func_array([$controller, $action], $params);
                    return;
                }
            }
        }

        $this->dispatchNotFound();
    }

    public function dispatchNotFound(): void
    {
        $this->response->setStatusCode(404);
        if (class_exists('App\\Controllers\\ErrorController')) {
            $controller = new \App\Controllers\ErrorController($this->request, $this->response);
            $controller->notFound();
            return;
        }
        echo "404 Not Found";
    }
}
