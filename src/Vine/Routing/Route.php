<?php

namespace Ketyl\Vine\Routing;

class Route
{
    protected string $uri;
    protected string $method;
    protected $callable;

    public function __construct(string $method, string $uri, $callable)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->callable = $callable;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getURI(): string
    {
        return $this->uri;
    }

    public function handle()
    {
        if (!$this->callable) {
            throw new \Exception;
        }

        return call_user_func($this->callable);
    }
}
