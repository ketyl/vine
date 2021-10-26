<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App extends Container
{
    protected $middlewareStack = [];

    public function __construct()
    {
        static::setGlobal($this);

        $this->register('router', Router::class);
        $this->register('response', Response::class);
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function run(): void
    {
        $this->bind('request', function () {
            return Request::createFromGlobals();
        });

        $this->handle()->send();
    }

    public function addMiddleware($callback)
    {
        $this->middlewareStack[] = $callback;
    }

    public function getGlobalMiddleware()
    {
        return $this->middlewareStack;
    }

    /**
     * Get the router instance.
     *
     * @return \Ketyl\Vine\Routing\Router
     */
    public static function router(): Router
    {
        return static::getGlobal()->get('router');
    }

    /**
     * Get the request instance.
     *
     * @return \Ketyl\Vine\Request
     */
    public static function request(): Request
    {
        return static::getGlobal()->get('request');
    }

    /**
     * Get the response instance.
     *
     * @return \Ketyl\Vine\Response
     */
    public static function response(): Response
    {
        return static::getGlobal()->get('response');
    }

    /**
     * Match the request to a route.
     *
     * @return \Ketyl\Vine\Response
     */
    private function handle(): Response
    {
        return $this->router()
            ->match($this->request())
            ->handle(
                $this->middlewareStack,
                request: $this->request(),
                response: $this->response(),
            );
    }
}
