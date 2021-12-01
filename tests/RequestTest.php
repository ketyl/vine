<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Request;

class RequestTest extends TestCase
{
    /** @test */
    function can_create_request()
    {
        $request = new Request(
            method: 'GET',
            uri: '/this/that',
        );

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/this/that', $request->getURI());
    }

    /** @test */
    function can_create_request_using_server_globals()
    {
        $_SERVER = [
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
        ];

        $request = Request::createFromGlobals();

        $this->assertEquals('/', $request->getURI());
        $this->assertEquals('GET', $request->getMethod());
    }

    /** @test */
    function creating_request_from_globals_trims_uri()
    {
        $_SERVER = [
            'REQUEST_URI' => '/hello/world/',
            'REQUEST_METHOD' => 'GET',
        ];

        $request = Request::createFromGlobals();

        $this->assertEquals('/hello/world', $request->getURI());
    }
}
