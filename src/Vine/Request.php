<?php

namespace Ketyl\Vine;

class Request
{
    /**
     * Create a new request instance.
     *
     * @param string $method
     * @param string $uri
     * @param mixed[]|null $server
     */
    public function __construct(
        protected string $method,
        protected string $uri,
        protected ?array $server = null
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->server = $server ?? $_SERVER;
    }

    /**
     * Create a request from the server environment variables.
     *
     * @param mixed[] $server
     * @return \Ketyl\Vine\Request
     */
    public static function createFromServer(array $server): Request
    {
        $uri = rtrim(parse_url($server['REQUEST_URI'])['path'], '/');

        return new Request(
            $server['REQUEST_METHOD'],
            $uri ? $uri : '/',
            $server,
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
