<?php

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];
    public static ?self $instance = null;

    public function __construct()
    {
        self::$instance = $this;
    }

    public function get(string $path, array $handler, array $middleware = [], ?string $name = null): void
    {
        $this->add('GET', $path, $handler, $middleware, $name);
    }

    public function post(string $path, array $handler, array $middleware = [], ?string $name = null): void
    {
        $this->add('POST', $path, $handler, $middleware, $name);
    }

    private function add(string $method, string $path, array $handler, array $middleware, ?string $name): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middleware', 'name');
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
    }

    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            return '/';
        }

        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', (string) $value, $path);
        }

        return $path;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            array_shift($matches);

            if ($method === 'POST' && !Csrf::checkRequest()) {
                http_response_code(419);
                echo 'Session expirée. Merci de rafraîchir la page et de réessayer.';
                return;
            }

            foreach ($route['middleware'] as $mw) {
                require_once __DIR__ . '/../middlewares/' . $mw . '.php';
                if (!(new $mw())->handle()) {
                    return;
                }
            }

            [$controllerName, $action] = $route['handler'];
            require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
            $controller = new $controllerName();
            $controller->$action(...$matches);
            return;
        }

        http_response_code(404);
        echo '404 - page introuvable';
    }
}
