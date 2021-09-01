<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\Response;

class ResponseTest extends TestCase
{
    /** @test */
    function can_create_response_with_data()
    {
        $response = new Response('some data');

        $this->assertNotNull($response->transform());
        $this->assertEquals('some data', $response->getData());
    }

    /** @test */
    function response_transformation_returns_null_for_empty_data()
    {
        $response = new Response([]);

        $this->assertNull($response->transform());
    }

    /** @test */
    function response_transforms_string()
    {
        $response = new Response('some data');

        $this->assertIsString($response->transform());
        $this->assertEquals('some data', $response->transform());
    }

    /** @test */
    function response_transforms_array()
    {
        $response = new Response(['this' => 'that']);

        $this->assertIsString($response->transform());
        $this->assertEquals('{"this":"that"}', $response->transform());
    }
}
