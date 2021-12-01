<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use Ketyl\Vine\Request;

class MiddlewareTest extends TestCase
{
    /** @test */
    function can_create_global_middleware()
    {
        $app = new App;

        $app->router()->addMiddleware(function () {
            return 'hello, world';
        });

        $this->assertCount(1, $app->router()->getMiddleware());
        $this->assertEquals('hello, world', $app->router()->getMiddleware()[0]());
    }

    /** @test */
    function can_create_route_specific_middleware()
    {
        $app = new App;

        $app->router()->get('/this', fn () => 'hello');

        $app->router()->get('/that', fn () => 'hello')
            ->addMiddleware(function ($request, $response, $next) {
                $response->write('BEFORE');
                return $next($request, $response);
            });

        $requestA = new Request('GET', '/this');
        $this->assertEquals(
            'hello',
            $app->router()->match($requestA)->handle($requestA)->getBody(),
        );

        $requestB = new Request('GET', '/that');
        $this->assertEquals(
            'BEFOREhello',
            $app->router()->match($requestB)->handle($requestB)->getBody(),
        );
    }

    /** @test */
    function middleware_can_call_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $app->router()->addMiddleware(function ($request, $response, $next) {
            return $next($request, $response);
        });

        $request = new Request('GET', '/');

        $this->assertEquals(
            'hello',
            $app->router()->match($request)->handle($request)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_before_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $app->router()->addMiddleware(function ($request, $response, $next) {
            $response->write('BEFORE');
            return $next($request, $response);
        });

        $request = new Request('GET', '/');

        $this->assertEquals(
            'BEFOREhello',
            $app->router()->match($request)->handle($request)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_after_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $app->router()->addMiddleware(function ($request, $response, $next) {
            $response = $next($request, $response);
            $response->write('AFTER');
            return $response;
        });

        $request = new Request('GET', '/');

        $this->assertEquals(
            'helloAFTER',
            $app->router()->match($request)->handle($request)->getBody()
        );
    }

    /** @test */
    function middleware_can_perform_action_before_and_after_next()
    {
        $app = new App;

        $app->router()->get('/', fn () => 'hello');

        $app->router()->addMiddleware(function ($request, $response, $next) {
            $response->write('BEFORE');
            $response = $next($request, $response);
            $response->write('AFTER');
            return $response;
        });

        $request = new Request('GET', '/');

        $this->assertEquals(
            'BEFOREhelloAFTER',
            $app->router()->match($request)->handle($request)->getBody()
        );
    }
}
