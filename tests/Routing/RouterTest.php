<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Request;
use Ketyl\Vine\Routing\Router;
use Ketyl\Vine\Tests\TestCase;

class RouterTest extends TestCase
{
    /** @test */
    function canCreateGetRoute()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $this->assertCount(1, $router->getRoutes());
        $this->assertEquals('GET', $router->getRoutes()[0]->getMethod());
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function canMatchRoute()
    {
        $request = new Request('GET', '/route');
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request));
    }

    /** @test */
    function canMatchRouteWithParameters()
    {
        $request = new Request('GET', '/route/hello');
        $router = new Router;
        $router->get('/route/{foo}', fn ($foo) => $foo);

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->handle($request));
    }

    /** @test */
    function canMatchRouteWithMultipleParameters()
    {
        $request = new Request('GET', '/route/hello/bar/world');
        $router = new Router;
        $router->get('/route/{foo}/bar/{baz}', fn ($foo, $bar) => $foo . ' ' . $bar);

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello world', $route->handle($request));
    }

    /** @test */
    function canMatchRouteWithRegex()
    {
        $request = new Request('GET', '/route/123');
        $router = new Router;
        $router->get('/route/[0-9]+', fn () => 'Hello, world!');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request));
    }
}
