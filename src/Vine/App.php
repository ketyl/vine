<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App extends Container
{
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

    /**
     * Get the router instance.
     *
     * @return \Ketyl\Vine\Routing\Router
     */
    public function router(): Router
    {
        return $this->get('router');
    }

    /**
     * Get the request instance.
     *
     * @return \Ketyl\Vine\Request
     */
    public function request(): Request
    {
        return $this->get('request');
    }

    /**
     * Get the response instance.
     *
     * @return \Ketyl\Vine\Response
     */
    public function response(): Response
    {
        return $this->get('response');
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
                request: $this->request(),
                response: $this->response(),
            );
    }
}
