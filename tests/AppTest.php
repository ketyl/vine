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
        $this->assertInstanceOf(Container::class, $this->app);
        $this->assertEquals($this->app, $this->app->getGlobal());
    }
}
