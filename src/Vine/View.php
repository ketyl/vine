<?php

namespace Ketyl\Vine;

use Exception;

class View
{
    public function __construct(
        protected string $body
    ) {
        $this->body = $body;
    }

    public static function createFromFile(string $filename): View
    {
        if (!file_exists($filename)) {
            throw new Exception(sprintf('File "%s" could not be found.', $filename));
        }

        return new static(file_get_contents($filename));
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
