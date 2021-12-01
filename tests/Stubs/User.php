<?php

namespace Ketyl\Vine\Tests\Stubs;

class User
{
    public function __construct(
        public string $firstName = '',
        public string $lastName = '',
    ) {}
}
