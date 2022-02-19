<?php

namespace Ketyl\Vine\Routing;

use Ketyl\Vine\App;
use Ketyl\Vine\Request;
use Ketyl\Vine\Response;
use Ketyl\Vine\Traits\HasMiddleware;

class Route
{
    use HasMiddleware;

    /**
     * Create a new request object.
     *
     * @param string[] $methods
     * @param string $pattern
     * @param mixed $callable
     * @param Parameter[] $parameters
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
            parameters: array_map(
                fn ($param) => Parameter::fromParts(...explode(':', $param)),
                $parameters[0]
            ),
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
     * Get the URI pattern matched by the route, excluding regex.
     *
     * @return string
     */
    public function getPattern(): string
    {
        return preg_replace('/:([^\/\{\}]+)\}/', '}', $this->pattern);
    }

    /**
     * Get the route parameters.
     *
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $name): Parameter
    {
        foreach ($this->parameters as $param) {
            if ($param->getName() == $name) {
                return $param;
            }
        }

        return null;
    }

    public function param(string $name, string $regex = '.*'): Route
    {
        $param = $this->getParameter($name);

        if (!$param) return $this;

        $param->setRegex($regex);

        return $this;
    }

    /**
     * Set the route parameter values.
     *
     * @param mixed[] $values
     * @return void
     */
    public function setParameterValues(array $params): void
    {
        foreach ($params as $key => $value) {
            $this->getParameter($key)->setValue($value);
        }
    }

    /**
     * Call the route's callable and pass it its parameters and generate a response.
     *
     * @param \Ketyl\Vine\Response $response
     * @param \Ketyl\Vine\Request $request
     * @return \Ketyl\Vine\Response
     */
    public function handle(Request $request, Response|null $response = null): Response
    {
        if (!$this->callable) {
            throw new \Exception;
        }

        $response = $response ?? new Response;

        $this->addMiddleware(App::router()->getRoutes()->getMiddleware());

        $callback = $this->executeMiddlewareStack(
            $request,
            $response,
            fn () => $response->write(call_user_func($this->callable, ...array_map(fn ($param) => $param->getValue(), $this->getParameters()))),
        );

        return call_user_func($callback);
    }
}
