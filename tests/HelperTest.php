<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\View;

class HelperTest extends TestCase
{
    /** @test */
    function view_helper_creates_new_view_instance()
    {
        $this->assertInstanceOf(View::class, view($this->getStubPath('test-view.html')));
    }
}
