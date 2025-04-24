<?php

namespace Framework;

use InvalidArgumentException;

class Response
{
    /**
     * Send a normal HTML/text response.
     * @param string $body The body of the response.
     * @param int $status The status code of the response.
     * @param array<string, string> $headers The headers for the response.
     * @return never After this function is called the request is effectively over.
     */
    public static function send(string $body, int $status = 200, array $headers = []): never
    {
        http_response_code($status);

        foreach ($headers as $name => $value) {
            header("$name: $value");
        }

        echo $body;

        exit;
    }

    /**
     * Send a JSON response.
     * @param mixed $data The json data, can be any type except a resource.
     * @param int $status The status code of the response.
     * @return never After this function is called the request is effectively over.
     */
    public static function json(mixed $data, int $status = 200): never
    {
        $json = json_encode($data);
        if (!$json) {
            throw new InvalidArgumentException('Unable to parse json response');
        }

        self::send($json, $status, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Redirect to a different location.
     * @param string $url The url to redirect to.
     * @param int $status The status of the request.
     * @return never After this function is called the request is effectively over.
     */
    public static function redirect(string $url, int $status = 302): never
    {
        header("Location: $url", true, $status);

        exit;
    }
}
