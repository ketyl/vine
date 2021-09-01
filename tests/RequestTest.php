<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Request;

class RequestTest extends TestCase
{
    /** @test */
    function can_create_request_using_server_globals()
    {
        $server = [
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
        ];

        $request = Request::createFromServer($server);

        $this->assertEquals('/', $request->getURI());
        $this->assertEquals('GET', $request->getMethod());
    }

    /** @test */
    function creating_request_from_globals_trims_uri()
    {
        $server = [
            'REQUEST_URI' => '/hello/world/',
            'REQUEST_METHOD' => 'GET',
        ];

        $request = Request::createFromServer($server);

        $this->assertEquals('/hello/world', $request->getURI());
    }
}
