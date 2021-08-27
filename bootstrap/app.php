<?php

use Ketyl\Vine\App;
use App\Http\Controllers\HomeController;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App();
$router = $app->router();

$router->get('/', function () {
    return 'Hello, world!';
});

$router->get('/test', function () {
    return 'Test!';
});

$router->get('/class', [HomeController::class, 'index']);
