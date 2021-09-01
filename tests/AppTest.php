<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use Ketyl\Vine\Routing\Router;

class AppTest extends TestCase
{
    /** @test */
    function can_create_app_instance()
    {
        $app = new App;

        $this->assertInstanceOf(Router::class, $app->router());
    }
}