<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use Ketyl\Vine\Container;
use Ketyl\Vine\Routing\Router;

class AppTest extends TestCase
{
    /** @test */
    function app_instance_uses_container()
    {
        $app = new App;

        $this->assertInstanceOf(Container::class, $app);
        $this->assertEquals($app, $app->getGlobal());
    }
}
