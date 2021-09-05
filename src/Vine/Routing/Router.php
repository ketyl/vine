<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Exceptions\NotFoundException;

class Router
{
    /**
     * Create a new router instance.
     *
     * @param Route[]|null $routes
     */
    public function __construct(
        protected ?array $routes = null
    ) {
        $this->routes = $routes;
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
            $parameters = [];

            if (!$this->requestMatchesRoute($request, $route, $parameters)) {
                continue;
            }

            $route->setParameters($parameters);

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
        return $this->addRoute(Route::create(
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

        return null;
    }

    /**
     * Get the registered routes.
     *
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Add a route to the router's collection.
     *
     * @param \Ketyl\Vine\Routing\Route $route
     * @return \Ketyl\Vine\Routing\Route
     */
    private function addRoute(Route $route): Route
    {
        $this->routes[] = $route;

        return $route;
    }

    /**
     * Generate a callable given a class name and a method to call.
     *
     * @param string $class
     * @param string $method
     * @return mixed
     */
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
     * Determine if a route accepts a request.
     *
     * @param \Ketyl\Vine\Request $request
     * @param \Ketyl\Vine\Routing\Route $route
     * @param mixed[] $parameters
     * @return boolean
     */
    private function requestMatchesRoute(Request $request, Route $route, array &$parameters = []): bool
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
