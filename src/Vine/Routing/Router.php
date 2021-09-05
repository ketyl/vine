<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Exceptions\NotFoundException;

class Router
{
    public function __construct(
        protected ?array $routes = null
    ) {
        $this->routes = $routes;
    }

    public function match(Request $request): Route
    {
        foreach ($this->getRoutes() as $route) {
            $parameters = [];

            if (!$this->requestMatchesRoute($request, $route, $parameters)) {
                continue;
            }

            $route->setParameters($parameters);

            return $route;
        }

        throw new NotFoundException;
    }

    public function get(string $pattern, mixed $callable): Route
    {
        return $this->addRoute(Route::create(
            methods: ['GET', 'HEAD'],
            pattern: $pattern,
            callable: $this->mutateCallable($callable),
        ));
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

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function addRoute(Route $route): Route
    {
        $this->routes[] = $route;

        return $route;
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

    public function requestMatchesRoute(Request $request, Route $route, array &$parameters = []): bool
    {
        if (!$route->acceptsMethod($request->getMethod())) return false;

        $routeUri = preg_replace(
            '/(?=.*[^\.])\*(?=.*)/',
            '.*',
            str_replace('/', '\/', $route->getPattern())
        );

        preg_match('/\{([^\/\{\}]+)\}/', $routeUri, $regexPartParams);

        if (!$regexPartParams) {
            $regexPart = '[^\/\{\}]+';
        } else {
            array_shift($regexPartParams);
            $regexPart = explode(':', $regexPartParams[0])[1] ?? '[^\/\{\}]+';
        }

        $match = preg_match(
            '/^' . preg_replace('/\{[^\/\{\}]+\}/', '(' . $regexPart . ')', $routeUri) . '$/',
            $request->getURI(),
            $matches,
        );

        if (!$match) return false;

        array_shift($matches);

        $parameters = array_combine(
            array_map(
                fn ($item) => explode(':', $item)[0],
                $route->getParameters()
            ),
            $matches
        );

        return true;
    }
}
