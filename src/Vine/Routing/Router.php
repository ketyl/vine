<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Exceptions\NotFoundException;

class Router
{
    private array $routes = [];

    public function matchRoute(string $method, string $pattern): Route
    {
        $pattern = $pattern ? rtrim($pattern, '/') : '/';

        foreach ($this->getRoutes() as $route) {
            if (!$route->acceptsMethod($method)) continue;

            $matches = [];
            $match = preg_match(
                '/^' . preg_replace('/\{[^\/\{\}]+\}/', '([^\/\{\}]+)', str_replace('/', '\/', $route->getPattern())) . '$/',
                $pattern,
                $matches
            );

            if (!$match) continue;

            array_shift($matches);

            $route->setParameters(
                array_combine($route->getParameters(), $matches)
            );

            return $route;
        }

        throw new NotFoundException;
    }

    public function get(string $pattern, mixed $callable): Router
    {
        $this->routes[] = Route::create(
            'GET',
            pattern: $pattern,
            callable: $this->mutateCallable($callable),
        );

        return $this;
    }

    public function mutateCallable(mixed $callable): mixed
    {
        if (is_callable($callable)) {
            return $callable;
        }

        if (is_array($callable) && sizeof($callable) == 2) {
            return $this->loadClass($callable[0], $callable[1]);
        }

        return null;
    }

    private function loadClass(string $class, string $method): mixed
    {
        if (!class_exists($class)) {
            throw new \Exception;
        }

        if (!method_exists($class, $method)) {
            throw new \Exception;
        }

        return [new $class, $method];
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
