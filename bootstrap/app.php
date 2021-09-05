<?php

use Ketyl\Vine\App;

class HomeController
{
    public function index()
    {
        return 'Hello, class!';
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

$app = App::create();
$router = $app->router();

$router->get('/', fn () => 'Hello, world!');
$router->get('/test', fn () => 'Test!');

$router->get('/view', fn () => view(__DIR__ . '/../views/index.html'));

$router->get('/class', [HomeController::class, 'index']);

$router->get('/param/{foo:\d+}', fn ($foo) => 'Your number is ' . $foo);
$router->get('/param/{foo}', fn ($foo) => $foo);
$router->get('/param/{foo}/{bar}', fn ($foo, $bar) => [$foo, $bar]);

$router->get('*', fn () => 'Catch all!');
