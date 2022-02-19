<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Request;
use Ketyl\Vine\Routing\Router;
use Ketyl\Vine\Tests\TestCase;
use Ketyl\Vine\Exceptions\NotFoundException;

class RouterTest extends TestCase
{
    /** @test */
    function can_create_route_using_a_closure()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $this->assertCount(1, $router->getRoutes());
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function can_create_route_using_a_class_method()
    {
        $router = new Router;
        $router->get('/route', [\Ketyl\Vine\Tests\Stubs\DemoController::class, 'index']);

        $this->assertCount(1, $router->getRoutes());
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function can_create_route_using_invoke()
    {
        $router = new Router;
        $router->get('/route', \Ketyl\Vine\Tests\Stubs\DemoController::class);

        $this->assertCount(1, $router->getRoutes());
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function can_create_get_route()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');

        $this->assertCount(1, $router->getRoutes());
        $this->assertTrue($router->getRoutes()[0]->acceptsMethod('GET'));
        $this->assertTrue($router->getRoutes()[0]->acceptsMethod('HEAD'));
        $this->assertEquals('/route', $router->getRoutes()[0]->getPattern());
        $this->assertEquals([], $router->getRoutes()[0]->getParameters());
    }

    /** @test */
    function can_match_route()
    {
        $router = new Router;
        $router->get('/route', fn () => 'Hello, world!');
        $request = new Request('GET', '/route');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request)->getBody());
    }

    /** @test */
    function can_match_route_with_parameters()
    {
        $router = new Router;
        $router->get('/route/{foo}', fn ($foo) => $foo);
        $request = new Request('GET', '/route/hello');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->getParameter('foo')->getValue());
        $this->assertEquals('hello', $route->handle($request)->getBody());
    }

    /** @test */
    function can_match_route_with_multiple_parameters()
    {
        $router = new Router;
        $router->get('/route/{foo}/bar/{baz}', fn ($foo, $baz) => $foo . ' ' . $baz);
        $request = new Request('GET', '/route/hello/bar/world');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('hello', $route->getParameter('foo')->getValue());
        $this->assertEquals('world', $route->getParameter('baz')->getValue());
        $this->assertEquals('hello world', $route->handle($request)->getBody());
    }

    /** @test */
    function can_match_route_with_regex()
    {
        $router = new Router;
        $router->get('/route/[0-9]+', fn () => 'Hello, world!');
        $request = new Request('GET', '/route/123');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request)->getBody());
    }

    /** @test */
    function can_match_route_with_wildcard()
    {
        $router = new Router;
        $router->get('/route/*', fn () => 'Hello, world!');
        $request = new Request('GET', '/route/123');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('Hello, world!', $route->handle($request)->getBody());
    }

    /** @test */
    function can_match_route_parameter_with_regex()
    {
        $router = new Router;
        $router->get('/route/{foo:\d+}', fn () => 'Hello, world!');
        $request = new Request('GET', '/route/123');

        $route = $router->match($request);
        $this->assertNotNull($route);
        $this->assertEquals('123', $route->getParameter('foo')->getValue());
        $this->assertEquals('Hello, world!', $route->handle($request)->getBody());
    }

    /** @test */
    function route_parameter_with_regex_doesnt_falsly_match()
    {
        $router = new Router;
        $router->get('/route/{foo:\d+}', fn () => 'Hello, world!');
        $request = new Request('GET', '/route/1d23');

        $this->expectException(NotFoundException::class);
        $router->match($request);
    }
}
