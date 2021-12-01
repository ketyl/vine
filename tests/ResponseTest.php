<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Response;

class ResponseTest extends TestCase
{
    /** @test */
    function can_set_status_of_response()
    {
        $response = new Response;

        $response->setStatus(404);

        $this->assertEquals(404, $response->getStatus());
    }
    /** @test */
    function can_write_a_string_to_the_response_body()
    {
        $response = (new Response())->write('some data');

        $this->assertEquals('some data', $response->getBody());
    }

    /** @test */
    function can_write_empty_array_to_response_body()
    {
        $response = (new Response)->write([]);

        $this->assertEquals('[]', $response->getBody());
    }

    /** @test */
    function can_write_array_to_response_body()
    {
        $response = (new Response)->write(['this' => 'that']);

        $this->assertIsString($response->getBody());
        $this->assertEquals('{"this":"that"}', $response->getBody());
    }

    /** @test */
    function can_add_a_header_to_a_response()
    {
        $response = new Response;

        $this->assertEquals([], $response->getHeaders());

        $response->addHeader('this', 'that');

        $this->assertEquals(['this' => 'that'], $response->getHeaders());
    }

    /** @test */
    function can_add_headers_using_array()
    {
        $response = new Response;

        $this->assertEquals([], $response->getHeaders());

        $response->addHeader(['this' => 'that', 'foo' => 'bar']);

        $this->assertEquals(['this' => 'that', 'foo' => 'bar'], $response->getHeaders());
    }
}
