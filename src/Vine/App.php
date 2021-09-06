<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App extends Container
{
    public function __construct()
    {
        static::setInstance($this);

        $this->register('router', Router::class);
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function run(): void
    {
        $this->handle(
            Request::createFromGlobals()
        )->send();
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
     * Match the request to a route.
     *
     * @param \Ketyl\Vine\Request $request
     * @return \Ketyl\Vine\Response
     */
    private function handle(Request $request): Response
    {
        return $this->router()
            ->match($request)
            ->handle($request);
    }
}
