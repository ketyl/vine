<?php

use Ketyl\Vine\View;

if (!function_exists('view')) {
    function view(string $filename): View
    {
        return View::createFromFile($filename);
    }
}
