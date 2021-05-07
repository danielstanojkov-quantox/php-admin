<?php

namespace App\Helpers;

class Server
{
    /**
     *  Check method type of the request 
     *
     * @param string $method
     * @return bool
     */
    public static function requestIs($method): bool
    {
        return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
    }
}
