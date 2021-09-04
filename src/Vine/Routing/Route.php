<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;
use Ketyl\Vine\Response;

class Route
{
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

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function acceptsMethod(string|array $method): bool
    {
        return in_array($method, $this->methods);
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $values): array
    {
        return $this->parameters = $values;
    }

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
