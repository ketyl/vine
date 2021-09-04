<?php

use Ketyl\Vine\View;

if (!function_exists('view')) {
    function view(string $filename)
    {
        return View::createFromFile($filename);
    }
}
