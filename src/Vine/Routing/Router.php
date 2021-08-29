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
        $uri = $uri ? $uri : '/';

        foreach ($this->routes as $route) {
            if (!$route->acceptsMethod($method)) continue;

            $pattern = '/^' . preg_replace('/\{[^\/\{\}]+\}/', '([^\/\{\}]+)', str_replace('/', '\/', $route->getURI())) . '$/';
            $matches = [];
            $match = preg_match($pattern, $uri, $matches);

            if (!$match) continue;

            array_shift($matches);

            $route->setParameters(
                array_combine($route->getParameters(), $matches)
            );

            return $route;
        }

        throw new NotFoundException;
    }

    public function get(string $uri, mixed $callable)
    {
        $parameters = [];
        preg_match_all('/(?!\{)[^\/\{\}]+(?=\})/', $uri, $parameters);

        $this->routes[] = new Route(
            method: 'GET',
            uri: $uri,
            callable: $this->mutateCallable($callable),
            parameters: $parameters[0],
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
