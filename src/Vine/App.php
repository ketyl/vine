<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App
{
    public function __construct(
        protected ?Router $router = null
    ) {
        $this->router = $router ?? new Router;
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function run(): void
    {
        $request = Request::createFromServer($_SERVER);

        print($this->handle($request)->transform());
    }

    /**
     * Get the router instance.
     *
     * @return \Ketyl\Vine\Routing\Router|null
     */
    public function router(): Router|null
    {
        return $this->router;
    }

    /**
     * Match the request to a route.
     *
     * @param \Ketyl\Vine\Request $request
     * @return \Ketyl\Vine\Response
     */
    private function handle(Request $request): Response
    {
        return $this->router->match($request)->handle($request);
    }
}
