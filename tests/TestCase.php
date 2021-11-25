<?php

namespace Ketyl\Vine\Tests;

use Ketyl\Vine\App;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected App $app;

    public function setUp(): void
    {
        $this->app = new App;
    }

    protected function getStubPath(string $filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Stubs' . DIRECTORY_SEPARATOR . $filename;
    }
}
