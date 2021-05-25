<?php

namespace App\Http\Middleware;

class IsAuthenticatedMiddleware
{
    /**
     * Allow access if the user is authenticated
     *
     * @return bool
     */
    public static function handle(): bool
    {
        return isset($_COOKIE['user_id']);
    }
}
