<?php

namespace Ketyl\Vine;

use Ketyl\Vine\Routing\Router;

class App
{
    protected $router;

    public function __construct()
    {
        $this->router = new Router;
    }

    public function run(): mixed
    {
        $request = Request::createFromServer($_SERVER);
        $response = $this->handle($request);

        return $response->transform();
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
