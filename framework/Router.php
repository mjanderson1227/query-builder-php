<?php

namespace Framework;

/**
 * Router
 *
 * A simple object-oriented router for handling HTTP requests.
 */
class Router
{
    /**
     * The route table, organized by HTTP method and path.
     *
     * @var array<string, array<string, callable>>
     */
    private array $routes = [];

    /**
     * Register a route that responds to GET requests.
     *
     * @param  string  $path  The URI path (e.g., '/about').
     * @param  callable  $handler  The function or controller method to handle the request.
     */
    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a route that responds to POST requests.
     *
     * @param  string  $path  The URI path.
     * @param  callable  $handler  The function or controller method to handle the request.
     */
    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Adds a route to the route table.
     *
     * @param  string  $method  The HTTP method (GET, POST, etc.).
     * @param  string  $path  The URI path.
     * @param  callable  $handler  The handler function.
     */
    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    /**
     * Dispatches the current request to the appropriate route handler.
     *
     * @param  Request  $request  The incoming request.
     */
    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();
        $url = parse_url($uri, PHP_URL_PATH);

        if (! empty($this->routes[$method][$url])) {
            try {
                call_user_func($this->routes[$method][$url], $request);
            } catch (\Throwable $th) {
                http_response_code(500);
                echo "500 Internal Server Error {$th->getMessage()}";
            }

            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
