<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App
{
    /**
     * @var \Ketyl\Vine\Routing\Router
     */
    protected Router $router;

    /**
     * Create a new App instance.
     *
     * @param \Ketyl\Vine\Routing\Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Create a new instance of Vine.
     *
     * @param \Ketyl\Vine\Routing\Router|null $router
     * @return \Ketyl\Vine\App
     */
    public static function create(Router $router = null): App
    {
        return new App(
            $router ?? new Router,
        );
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function run(): void
    {
        $request = Request::createFromGlobals();
        $response = $this->handle($request);
        $response->emit();
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
