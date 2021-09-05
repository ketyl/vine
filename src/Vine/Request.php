<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Route;

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

    public static function createFromServer(array $server)
    {
        $uri = rtrim(parse_url($server['REQUEST_URI'])['path'], '/');

        return new Request(
            $server['REQUEST_METHOD'],
            $uri ? $uri : '/',
            $server,
        );
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getURI()
    {
        return $this->uri;
    }
}
