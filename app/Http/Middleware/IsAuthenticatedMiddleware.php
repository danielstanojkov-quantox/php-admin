<?php

namespace App\Http\Middleware;

use App\Helpers\Cookie;

class IsAuthenticatedMiddleware
{
    /**
     * Allow access if the user is authenticated
     *
     * @param  $request
     * @return bool
     */
    public static function handle(): bool
    {
        return Cookie::exists('user_id');
    }
}
