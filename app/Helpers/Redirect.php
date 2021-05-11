<?php

namespace App\Helpers;

class Redirect
{
    /**
     * Redirects the user to specified URI
     *
     * @param string $uri
     * @return void
     */
    public static function to(string $uri): void
    {
        $uri = URLROOT . $uri;
        header("Location: $uri");
        die;
    }
}
