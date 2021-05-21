<?php

namespace App\Helpers;

class Server
{
    /**
     * Return the current request's type
     *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Return's request object
     *
     * @return array
     */
    public function getRequest(): array
    {
        return $_SERVER;
    }

    /**
     * Get full url
     *
     * @return string
     */
    public function fullUrl(): string
    {
        return fullUrl();
    }
}
