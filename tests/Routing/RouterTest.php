<?php

namespace Ketyl\Vine\Tests;

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
        $this->assertEquals('Hello, world!', $router->getRoutes()[0]->handle());
    }

    /** @test */
    function canMatchRoute()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $route = $router->matchRoute('GET', '/route');
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle());
    }

    /** @test */
    function canMatchRouteWithTrailingSlash()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $route = $router->matchRoute('GET', '/route/');
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle());
    }

    /** @test */
    function canMatchRouteWithParameters()
    {
        $router = new Router;
        $router->get('/route/{foo}', fn ($foo) => $foo);

        $route = $router->matchRoute('GET', '/route/hello');
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->handle());
    }

    /** @test */
    function canMatchRouteWithMultipleParameters()
    {
        $router = new Router;
        $router->get('/route/{foo}/bar/{baz}', fn ($foo, $bar) => $foo . ' ' . $bar);

        $route = $router->matchRoute('GET', '/route/hello/bar/world');
        $this->assertNotNull($route);
        $this->assertEquals('hello world', $route->handle());
    }

    /** @test */
    function canMatchRouteWithRegex()
    {
        $router = new Router;
        $router->get('/route/[0-9]+', fn () => 'Hello, world!');

        $route = $router->matchRoute('GET', '/route/123');
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle());
    }
}
