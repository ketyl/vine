<?php

namespace Ketyl\Vine\Routing;

class Route
{
    protected string $uri;
    protected string $method;
    protected $callable;
    protected array $parameters;

    public function __construct(string $method, string $uri, $callable, array $parameters)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function acceptsMethod(string $method): bool
    {
        return $this->method == $method;
    }

    public function getURI(): string
    {
        return $this->uri;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $values): array
    {
        return $this->parameters = $values;
    }

    public function handle()
    {
        if (!$this->callable) {
            throw new \Exception;
        }

        return call_user_func($this->callable, ...array_values($this->getParameters()));
    }
}
