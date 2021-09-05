<?php

namespace Ketyl\Vine;

class Request
{
    public function __construct(
        protected string $method,
        protected string $uri,
        protected ?array $server = null
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->server = $server ?? $_SERVER;
    }

    public static function createFromServer(array $server): Request
    {
        $uri = rtrim(parse_url($server['REQUEST_URI'])['path'], '/');

        return new Request(
            $server['REQUEST_METHOD'],
            $uri ? $uri : '/',
            $server,
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getURI(): string
    {
        return $this->uri;
    }
}
