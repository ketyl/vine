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

    public function run(): void
    {
        $request = Request::createFromServer($_SERVER);

        print($this->handle($request)->transform());
    }

    private function handle(Request $request): Response
    {
        return $this->router->match($request)->handle($request);
    }

    public function router(): Router
    {
        return $this->router;
    }
}
