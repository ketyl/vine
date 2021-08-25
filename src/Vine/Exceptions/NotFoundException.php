<?php

namespace Ketyl\Vine\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Could not find route.';
}
