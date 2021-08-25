<?php

use Ketyl\Vine\App;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App();

$app->router()->get('/', function () {
    return 'Hello, world!';
});

$app->router()->get('/test', function () {
    return 'Test!';
});
