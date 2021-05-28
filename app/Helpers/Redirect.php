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
    public function to(string $uri): void
    {
        $uri = app('url_root') . $uri;
        header("Location: $uri");
        die;
    }
}
