<?php

namespace App\Helpers;

class Redirect 
{
    public static function To($uri)
    {
        $uri = URLROOT . $uri;
        header("Location: $uri");
        die;
    }
}