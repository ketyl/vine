<?php

namespace Ketyl\Vine\Tests\Stubs;

class DemoController
{
    public function __invoke()
    {
        return 'Invoked!';
    }

    public function index()
    {
        return 'Index!';
    }
}
