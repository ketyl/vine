<?php

namespace Ketyl\Vine\Routing;

use ArrayObject;
use ArrayIterator;
use IteratorAggregate;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Traits\HasMiddleware;

class RouteGroup extends ArrayObject implements IteratorAggregate
{
    use HasMiddleware;

    /**
     * Array of routes.
     *
     * @var Route[]
     */
    protected array $routes = [];

    /**
     * Create a new route group.
     *
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Add a route to the group.
     *
     * @param \Ketyl\Vine\Routing\Route $route
     * @return \Ketyl\Vine\Routing\Route
     */
    public function add(Route $route)
    {
        $this->routes[] = $route;

        return $route;
    }

    /**
     * Get iterator for route group.
     *
     * @return ArrayIterator<Route>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->routes);
    }

    public function offsetGet($name): Route
    {
        return $this->routes[$name];
    }

    public function offsetSet($name, $value): void
    {
        $this->routes[$name] = $value;
    }

    public function offsetExists($name): bool
    {
        return isset($this->routes[$name]);
    }

    public function offsetUnset($name): void
    {
        unset($this->routes[$name]);
    }

    public function count(): int
    {
        return count($this->routes);
    }
}
