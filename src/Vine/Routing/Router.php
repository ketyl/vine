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

    public function get(string $uri, mixed $callable)
    {
        $this->routes[] = new Route(
            method: 'GET',
            uri: $uri,
            callable: $this->mutateCallable($callable),
        );

        return $this;
    }

    public function mutateCallable(mixed $callable)
    {
        if (is_callable($callable)) {
            return $callable;
        }

        if (is_array($callable) && sizeof($callable) == 2) {
            return $this->loadClass($callable[0], $callable[1]);
        }

        return null;
    }

    private function loadClass(string $class, string $method)
    {
        if (!class_exists($class)) {
            throw new \Exception;
        }

        if (!method_exists($class, $method)) {
            throw new \Exception;
        }

        return [new $class, $method];
    }
}
