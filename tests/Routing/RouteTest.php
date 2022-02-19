<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Routing\Route;
use Ketyl\Vine\Tests\TestCase;
use Ketyl\Vine\Routing\Parameter;

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

        $this->assertCount(1, $route->getParameters());
        $this->assertEquals('foo', $route->getParameter('foo')->getName());
        $this->assertEquals(Parameter::DEFAULT_PATTERN, $route->getParameter('foo')->getPattern());
    }

    /** @test */
    function can_create_route_with_multiple_parameters()
    {
        $route = Route::create('GET', '/route/{foo}/{bar}', fn () => 'Hello, world!');

        $this->assertCount(2, $route->getParameters());
        $this->assertEquals('foo', $route->getParameter('foo')->getName());
        $this->assertEquals('bar', $route->getParameter('bar')->getName());
    }

    /** @test */
    function can_create_route_parameter_with_regex()
    {
        $route = Route::create('GET', '/route/{foo:\d+}', fn () => 'Hello, world!');

        $this->assertCount(1, $route->getParameters());
        $this->assertEquals('foo', $route->getParameter('foo')->getName());
        $this->assertEquals('\d+', $route->getParameter('foo')->getPattern());
    }
}
