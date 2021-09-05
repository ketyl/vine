<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;
use Ketyl\Vine\Response;

class Route
{
    /**
     * Create a new request object.
     *
     * @param string[] $methods
     * @param string $pattern
     * @param mixed $callable
     * @param string[] $parameters
     */
    public function __construct(
        protected array $methods,
        protected string $pattern,
        protected mixed $callable,
        protected array $parameters,
    ) {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    /**
     * Create a new request object and extract parameters from URI.
     *
     * @param string[]|string $methods
     * @param string $pattern
     * @param mixed $callable
     * @return \Ketyl\Vine\Routing\Route
     */
    public static function create(array|string $methods, string $pattern, mixed $callable): Route
    {
        preg_match_all('/(?!\{)[^\/\{\}]+(?=\})/', $pattern, $parameters);

        return new Route(
            methods: is_array($methods) ? $methods : [$methods],
            pattern: $pattern,
            callable: $callable,
            parameters: $parameters[0],
        );
    }

    /**
     * Get the methods accepted by the route.
     *
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Determine if the route accepts the given method(s).
     *
     * @param string[]|string $method
     * @return boolean
     */
    public function acceptsMethod(string|array $method): bool
    {
        return in_array($method, $this->methods);
    }

    /**
     * Get the URI pattern matched by the route.
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Get the route parameters.
     *
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Set the route parameters.
     *
     * @param mixed[] $values
     * @return mixed[]
     */
    public function setParameters(array $values): array
    {
        return $this->parameters = $values;
    }

    /**
     * Call the route's callable and pass it its parameters.
     *
     * @param \Ketyl\Vine\Request $request
     * @return \Ketyl\Vine\Response
     */
    public function handle(Request $request): Response
    {
        if (!$this->callable) {
            throw new \Exception;
        }

        return new Response(
            call_user_func($this->callable, ...array_values($this->getParameters()))
        );
    }
}
