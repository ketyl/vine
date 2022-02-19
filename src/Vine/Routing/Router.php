<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Traits\HasMiddleware;
use Ketyl\Vine\Exceptions\NotFoundException;

class Router
{
    protected RouteGroup $routes;

    /**
     * Create a new router instance.
     *
     * @param Route[] $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = new RouteGroup($routes);
    }

    /**
     * Attempt to match a route to a request's URI.
     *
     * @param \Ketyl\Vine\Request $request
     * @return \Ketyl\Vine\Routing\Route
     */
    public function match(Request $request): Route
    {
        foreach ($this->getRoutes() as $route) {
            if (!$this->requestMatchesRoute($request, $route)) {
                continue;
            }

            return $route;
        }

        throw new NotFoundException;
    }

    /**
     * Register a GET endpoint.
     *
     * @param string $pattern
     * @param mixed $callable
     * @return \Ketyl\Vine\Routing\Route
     */
    public function get(string $pattern, mixed $callable): Route
    {
        return $this->getRoutes()->add(Route::create(
            methods: ['GET', 'HEAD'],
            pattern: $pattern,
            callable: $this->mutateCallable($callable),
        ));
    }

    /**
     * Mutate the callable to ensure it is valid.
     *
     * @param mixed $callable
     * @return mixed
     */
    public function mutateCallable(mixed $callable): mixed
    {
        if (is_callable($callable)) {
            return $callable;
        }

        if (is_array($callable) && sizeof($callable) == 2) {
            return $this->loadClass($callable[0], $callable[1]);
        }

        if (class_exists($callable)) {
            return $this->loadClass($callable);
        }

        return null;
    }

    /**
     * Get the registered routes.
     *
     * @return RouteGroup
     */
    public function getRoutes(): RouteGroup
    {
        return $this->routes;
    }

    /**
     * Generate a callable given a class name and a method to call.
     *
     * @param string $class
     * @param string $method
     * @return mixed
     */
    private function loadClass(string $class, string $method = '__invoke'): mixed
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
     * Determine if a route accepts a request.
     *
     * @param \Ketyl\Vine\Request $request
     * @param \Ketyl\Vine\Routing\Route $router
     * @return boolean
     */
    private function requestMatchesRoute(Request $request, Route $route): bool
    {
        if (!$route->acceptsMethod($request->getMethod())) return false;

        // Replace wildcard with default search regex
        $search = preg_replace(
            '/(?=.*[^\.])\*(?=.*)/',
            Parameter::DEFAULT_PATTERN,
            str_replace(['/', '.*'], ['\/', Parameter::DEFAULT_PATTERN], $route->getPattern())
        );

        if (!$search) return false;

        $paramNames = [];
        foreach ($route->getParameters() as $param) {
            $paramNames[] = $param->getName();
            $search = str_replace(sprintf('{%s}', $param->getName()), '(' . $param->getPattern() . ')', $search);
        }

        $routeMatches = preg_match(
            sprintf('/^%s$/', $search),
            $request->getURI(),
            $matches,
        );

        if (!$routeMatches) return false;

        array_shift($matches);
        $route->setParameterValues(array_combine($paramNames, $matches));

        return true;
    }
}
