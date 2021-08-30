<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    function canCreateRoute()
    {
        $route = Route::create('GET', '/route', fn () => 'Hello, world!');

        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals('/route', $route->getPattern());
        $this->assertEquals([], $route->getParameters());
    }

    /** @test */
    function routeCanDetermineIfAcceptsMethod()
    {
        $route = Route::create('GET', '/route', fn () => 'Hello, world!');

        $this->assertTrue($route->acceptsMethod('GET'));
    }

    /** @test */
    function canCreateRouteWithAParameter()
    {
        $route = Route::create('GET', '/route/{foo}', fn () => 'Hello, world!');

        $this->assertEquals(['foo'], $route->getParameters());
    }

    /** @test */
    function canCreateRouteWithMultipleParameters()
    {
        $route = Route::create('GET', '/route/{foo}/{bar}', fn () => 'Hello, world!');

        $this->assertEquals(['foo', 'bar'], $route->getParameters());
    }
}
