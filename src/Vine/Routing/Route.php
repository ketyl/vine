<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\Request;

class Route
{
    protected string $pattern;
    protected string $method;
    protected mixed $callable;
    protected array $parameters;

    public function __construct(string $method, string $pattern, mixed $callable, array $parameters)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    public static function create(string $method, string $pattern, mixed $callable): Route
    {
        $parameters = [];
        preg_match_all('/(?!\{)[^\/\{\}]+(?=\})/', $pattern, $parameters);

        return new Route(
            method: $method,
            pattern: $pattern,
            callable: $callable,
            parameters: $parameters[0],
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function acceptsMethod(string $method): bool
    {
        return $this->method == $method;
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

    public function handle(Request $request): mixed
    {
        if (!$this->callable) {
            throw new \Exception;
        }

        return call_user_func($this->callable, ...array_values($this->getParameters()));
    }
}
