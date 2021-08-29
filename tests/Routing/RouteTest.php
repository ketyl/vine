<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    function canCreateRoute()
    {
        $route = new Route('GET', '/route', fn () => 'Hello, world!', []);

        $this->assertEquals('GET', $route->getMethod());
        $this->assertEquals('/route', $route->getPattern());
        $this->assertEquals([], $route->getParameters());
    }

    /** @test */
    function routeCanDetermineIfAcceptsMethod()
    {
        $route = new Route('GET', '/route', fn () => 'Hello, world!', []);

        $this->assertTrue($route->acceptsMethod('GET'));
    }
}
