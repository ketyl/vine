<?php

namespace Ketyl\Vine\Routing;

use ArrayObject;
use Traversable;
use ArrayIterator;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Traits\HasMiddleware;

class RouteGroup extends ArrayObject
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
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->routes);
    }
}
