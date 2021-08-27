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

    public function run()
    {
        $route = $this->router->matchRoute($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'])['path']);

        return $this->render($route->handle());
    }

    public function render(mixed $response)
    {
        if (!$response) {
            return;
        }

        return match (gettype($response)) {
            'string' => print($response),
        };
    }

    public function router()
    {
        return $this->router;
    }
}
