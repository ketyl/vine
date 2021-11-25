<?php

namespace Ketyl\Vine\Traits;

use Ketyl\Vine\Request;
use Ketyl\Vine\Response;

trait HasMiddleware
{
    /**
     * Array of middleware that will be applied to the route.
     *
     * @var callable[]
     */
    protected array $middleware = [];

    /**
     * Add middleware to the route's middleware stack.
     *
     * @param callable|callable[] $middleware
     * @return void
     */
    public function addMiddleware(callable|array $middleware): void
    {
        if (is_array($middleware)) {
            foreach ($middleware as $item) {
                $this->middleware[] = $item;
            }
        } else {
            $this->middleware[] = $middleware;
        }
    }

    /**
     * Get all middleware registered to the route.
     *
     * @return callable[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Execute callback in middleware stack.
     *
     * @param \Ketyl\Vine\Request $request
     * @param \Ketyl\Vine\Response $response
     * @param callable $callback
     * @return callable
     */
    public function executeMiddlewareStack(Request $request, Response $response, callable $callback): callable
    {
        foreach ($this->getMiddleware() as $next) {
            $callback = fn () => $next($request, $response, $callback);
        }

        return $callback;
    }
}
