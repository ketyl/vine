<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Exceptions\NotFoundException;

class Router
{
    /**
     * @var Route[]
     */
    protected array $routes = [];

    public function matchRoute(string $method, string $uri)
    {
        foreach ($this->routes as $route) {
            if ($route->getMethod() != $method) {
                continue;
            }

            if ($route->getURI() != $uri) {
                continue;
            }

            return $route;
        }

        throw new NotFoundException;
    }

    public function get(string $uri, mixed $data)
    {
        $this->routes[] = new Route(
            method: 'GET',
            uri: $uri,
            callable: is_callable($data) ? $data : null,
        );

        return $this;
    }
}
