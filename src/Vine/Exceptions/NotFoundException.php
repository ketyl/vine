<?php

namespace Ketyl\Vine\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    /**
     * Exception message to display.
     *
     * @var string
     */
    protected $message = 'Could not find route.';
}
