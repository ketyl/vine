<?php

use Ketyl\Vine\App;

class HomeController
{
    public function index()
    {
        return 'Hello, class!';
    }

    public function view()
    {
        return view(__DIR__ . '/../views/index.php');
    }
}

class DownloadController
{
    public function __invoke($foo)
    {
        return 'Invoked with ' . $foo . '!';
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App;
$router = $app->router();

$router->get('/', fn () => 'Hello, world!');
$router->get('/test', fn () => 'Test!');

$router->get('/class', [HomeController::class, 'index']);
$router->get('/download/{foo}', DownloadController::class);

$router->get('/view', [HomeController::class, 'view']);

$router->get('/param/{foo:\d+}', fn ($foo) => 'Your number is ' . $foo);
$router->get('/param/{foo}', fn ($foo) => $foo);
$router->get('/param/{foo}/{bar}', fn ($foo, $bar) => [$foo, $bar]);

$router->get('*', fn () => 'Catch all!');
