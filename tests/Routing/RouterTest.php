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
        $this->assertTrue($router->getRoutes()[0]->acceptsMethod('GET'));
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function canMatchRoute()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');
        $request = new Request('GET', '/route');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request)->getData());
    }

    /** @test */
    function canMatchRouteWithParameters()
    {
        $router = new Router;
        $router->get('/route/{foo}', fn ($foo) => $foo);
        $request = new Request('GET', '/route/hello');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->getParameters()['foo']);
        $this->assertEquals('hello', $route->handle($request)->getData());
    }

    /** @test */
    function canMatchRouteWithMultipleParameters()
    {
        $router = new Router;
        $router->get('/route/{foo}/bar/{baz}', fn ($foo, $baz) => $foo . ' ' . $baz);
        $request = new Request('GET', '/route/hello/bar/world');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->getParameters()['foo']);
        $this->assertEquals('world', $route->getParameters()['baz']);
        $this->assertEquals('hello world', $route->handle($request)->getData());
    }

    /** @test */
    function canMatchRouteWithRegex()
    {
        $router = new Router;
        $router->get('/route/[0-9]+', fn () => 'Hello, world!');
        $request = new Request('GET', '/route/123');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request)->getData());
    }
}
