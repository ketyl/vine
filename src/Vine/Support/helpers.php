<?php

use Ketyl\Vine\View;

if (!function_exists('view')) {
    /**
     * Create a new view instance using a file.
     *
     * @param string $filename
     */
    function view(string $filename): View
    {
        return View::createFromFile($filename);
    }
}
