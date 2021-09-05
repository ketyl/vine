<?php

namespace Ketyl\Vine;

class Request
{
    /**
     * Create a new request instance.
     *
     * @param string $method
     * @param string $uri
     */
    public function __construct(
        protected string $method,
        protected string $uri,
    ) {
        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * Create a request from the server environment variables.
     *
     * @return \Ketyl\Vine\Request
     */
    public static function createFromGlobals(): Request
    {
        $uri = rtrim((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        return new Request(
            method: $_SERVER['REQUEST_METHOD'],
            uri: $uri ? $uri : '/',
        );
    }

    /**
     * Get the method of the request.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the URI of the request.
     *
     * @return string
     */
    public function getURI(): string
    {
        return $this->uri;
    }
}
