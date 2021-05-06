<?php

namespace App\Helpers;

use Carbon\Carbon;

class Cookie
{
    public static function get($key)
    {
    }

    public static function set(
        $name,
        $value = '',
        $expires = null,
        $path = '',
        $domain = '',
        $secure = false,
        $httponly = true
    ) {
        $expires = Carbon::now()->timestamp + (EXPIRATION_TIME * 60);
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
}
