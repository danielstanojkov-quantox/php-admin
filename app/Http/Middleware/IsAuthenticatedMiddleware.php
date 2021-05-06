<?php

namespace App\Http\Middleware;

use App\Helpers\Cookie;
use App\Helpers\Redirect;

class IsAuthenticatedMiddleware
{
    /**
     * Allow access if the user is authenticated
     *
     * @param  $request
     * @return string|null
     */
    public static function handle()
    {
        return Cookie::exists('user_id');
    }
}
