<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Tests\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    function can_create_route()
    {
        $route = Route::create('GET', '/route', fn () => 'Hello, world!');

        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals('/route', $route->getPattern());
        $this->assertEquals([], $route->getParameters());
    }

    /** @test */
    function route_can_determine_if_accepts_method()
    {
        $route = Route::create('GET', '/route', fn () => 'Hello, world!');

        $this->assertTrue($route->acceptsMethod('GET'));
    }

    /** @test */
    function can_create_route_with_a_parameter()
    {
        $route = Route::create('GET', '/route/{foo}', fn () => 'Hello, world!');

        $this->assertEquals(['foo'], $route->getParameters());
    }

    /** @test */
    function can_create_route_with_multiple_parameters()
    {
        $route = Route::create('GET', '/route/{foo}/{bar}', fn () => 'Hello, world!');

        $this->assertEquals(['foo', 'bar'], $route->getParameters());
    }
}
