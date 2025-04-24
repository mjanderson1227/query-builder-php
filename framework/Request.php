<?php

namespace Framework;

use InvalidArgumentException;

class Request
{
    private string $method;

    private string $uri;

    /** @var array<mixed> $get */
    private array $get;

    /** @var array<mixed> $post */
    private array $post;

    /** @var array<mixed> $server */
    private array $server;

    /** @var array<string, string> $headers */
    private array $headers;

    /** @var array<mixed> $files */
    private array $files;

    private mixed $body;

    /** @return string the uri from the superglobal */
    private function tryGetUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!is_string($uri)) {
            throw new InvalidArgumentException('Malformed request uri');
        }

        $parsed = parse_url($uri, PHP_URL_FRAGMENT);
        if (!is_string($parsed)) {
            throw new InvalidArgumentException('Malformed request uri');
        }

        return $parsed;
    }

    /** @return string the method parsed from the superglobal */
    private function tryGetMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!is_string($method)) {
            return 'GET';
        }

        return $method;
    }

    public function __construct()
    {
        $this->method = $this->tryGetMethod();
        $this->uri = $this->tryGetUri();
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->headers = $this->parseHeaders();
        $this->body = file_get_contents('php://input');
    }

    /**
     * Get the HTTP method (GET, POST, etc.).
     */
    public function method(): string
    {
        return strtoupper($this->method);
    }

    /**
     * Get the requested URI (without query string).
     */
    public function uri(): string
    {
        return rtrim($this->uri, '/') ?: '/';
    }

    /**
     * Get a query string value (e.g., from $_GET).
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Get a POST value (e.g., from $_POST).
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Get all query parameters.
     * @return array<mixed>
     */
    public function allQuery(): array
    {
        return $this->get;
    }

    /**
     * Get all post body parameters.
     * @return array<mixed>
     */
    public function allInput(): array
    {
        return $this->post;
    }

    /**
     * Get uploaded files.
     * @return array<mixed>
     */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * Get request headers.
     * @return array<mixed>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Get raw body (e.g., for JSON or PUT).
     * @return mixed
     */
    public function body(): mixed
    {
        return $this->body;
    }

    /**
     * Parse request headers from $_SERVER.
     * @return array<string, string> The array of headers
     */
    private function parseHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (!is_string($value)) {
                continue;
            }
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', substr($key, 5));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }
}
