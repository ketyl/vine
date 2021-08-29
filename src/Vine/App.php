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
        return $this->handle();
    }

    private function handle(): mixed
    {
        $route = $this->router->matchRoute($_SERVER['REQUEST_METHOD'], rtrim(parse_url($_SERVER['REQUEST_URI'])['path'], '/'));

        return $this->render($route->handle());
    }


    private function render(mixed $response): mixed
    {
        if (!$response) {
            return null;
        }

        return match (gettype($response)) {
            'string' => print($response),
            'array' => dump($response),
        };
    }

    public function router(): Router
    {
        return $this->router;
    }
}
