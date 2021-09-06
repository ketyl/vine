<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Response;

class ResponseTest extends TestCase
{
    /** @test */
    function can_write_a_string_to_the_response_body()
    {
        $response = (new Response())->write('some data');

        $this->assertEquals('some data', $response->getBody());
    }

    /** @test */
    function cannot_write_empty_array_to_response_body()
    {
        $response = (new Response)->write([]);

        $this->assertEmpty($response->getBody());
    }

    /** @test */
    function can_write_array_to_response_body()
    {
        $response = (new Response)->write(['this' => 'that']);

        $this->assertIsString($response->getBody());
        $this->assertEquals('{"this":"that"}', $response->getBody());
    }
}
