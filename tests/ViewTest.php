<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\View;

class ViewTest extends TestCase
{
    /** @test */
    function can_create_view()
    {
        $body = '<strong>Some text!</strong>';

        $view = new View($body);

        $this->assertEquals('<strong>Some text!</strong>', $view->getBody());
    }

    /** @test */
    function can_create_view_from_file()
    {
        $view = View::createFromFile($this->getStubPath('test-view.html'));

        $this->assertStringStartsWith('<strong>This is a test view!</strong>', $view->getBody());
    }

    /** @test */
    function cannot_create_view_from_file_that_doesnt_exist()
    {
        $this->expectExceptionMessageMatches('/.*could not be found.*/');

        View::createFromFile('nope.html');
    }
}
