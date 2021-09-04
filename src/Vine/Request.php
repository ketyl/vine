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

    public function matchesRoute(Route $route, array &$parameters = []): bool
    {
        if (!$route->acceptsMethod($this->getMethod())) return false;

        $match = preg_match(
            '/^' . preg_replace('/\{[^\/\{\}]+\}/', '([^\/\{\}]+)', str_replace('/', '\/', $route->getPattern())) . '$/',
            $this->getURI(),
            $matches,
        );

        if (!$match) return false;

        array_shift($matches);

        $parameters = array_combine($route->getParameters(), $matches);

        return true;
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
