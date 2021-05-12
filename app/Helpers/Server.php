<?php

namespace App\Helpers;

class Server
{
    /**
     * Return the current request's type
     *
     * @return string
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Return's request object
     *
     * @return array
     */
    public static function getRequest(): array
    {
        return $_SERVER;
    }
}
