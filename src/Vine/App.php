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

        return $this->handle($request);
    }

    private function handle(Request $request): mixed
    {
        $route = $this->router->match($request);

        return $this->createResponse($route->handle($request));
    }

    private function createResponse(mixed $data): mixed
    {
        if (!$data) {
            return null;
        }

        return match (gettype($data)) {
            'string' => print($data),
            'array' => print(json_encode($data)),
        };
    }

    public function router(): Router
    {
        return $this->router;
    }
}
