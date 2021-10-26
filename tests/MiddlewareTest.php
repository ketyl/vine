<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use Ketyl\Vine\Request;
use Ketyl\Vine\Response;

class MiddlewareTest extends TestCase
{
    /** @test */
    function can_create_global_middleware()
    {
        $app = new App;

        $app->addMiddleware(function () {
            return 'hello, world';
        });

        $this->assertCount(1, $app->getGlobalMiddleware());
        $this->assertEquals('hello, world', $app->getGlobalMiddleware()[0]());
    }

    /** @test */
    function middleware_can_call_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $middleware[] = function ($request, $response, $next) {
            return $next($request, $response);
        };

        $request = new Request('GET', '/');
        $response = new Response;

        $this->assertEquals(
            'hello',
            $app->router()->match($request)->handle($middleware, $request, $response)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_before_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $middleware[] = function ($request, $response, $next) {
            $response->write('BEFORE');
            return $next($request, $response);
        };

        $request = new Request('GET', '/');
        $response = new Response;

        $this->assertEquals(
            'BEFOREhello',
            $app->router()->match($request)->handle($middleware, $request, $response)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_after_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $middleware[] = function ($request, $response, $next) {
            $response = $next($request, $response);
            $response->write('AFTER');
            return $response;
        };

        $request = new Request('GET', '/');
        $response = new Response;

        $this->assertEquals(
            'helloAFTER',
            $app->router()->match($request)->handle($middleware, $request, $response)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_before_and_after_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $middleware[] = function ($request, $response, $next) {
            $response->write('BEFORE');
            $response = $next($request, $response);
            $response->write('AFTER');
            return $response;
        };

        $request = new Request('GET', '/');
        $response = new Response;

        $this->assertEquals(
            'BEFOREhelloAFTER',
            $app->router()->match($request)->handle($middleware, $request, $response)->getBody()
        );
    }
}
