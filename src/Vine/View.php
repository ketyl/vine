<?php

namespace Ketyl\Vine;

use Exception;

final class View
{
    /**
     * Create a new view class.
     *
     * @param string $body
     */
    public function __construct(
        protected string $body
    ) {
        $this->body = $body;
    }

    /**
     * Create a view using the contents of a file.
     *
     * @param string $filename
     * @return \Ketyl\Vine\View
     */
    public static function createFromFile(string $filename): View
    {
        if (!file_exists($filename)) {
            throw new Exception(sprintf('File "%s" could not be found.', $filename));
        }

        return new static(file_get_contents($filename));
    }

    /**
     * Get the body of the view.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
