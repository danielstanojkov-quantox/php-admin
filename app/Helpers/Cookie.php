<?php

namespace App\Helpers;

use Carbon\Carbon;

class Cookie
{
    public static function get($name)
    {
        return Hash::decrypt($_COOKIE[$name]);
    }

    public static function set(
        $name,
        $value = '',
        $expires = null,
        $path = '/',
        $domain = '',
        $secure = false,
        $httponly = false
    ) {
        $expires = Carbon::now()->timestamp + (EXPIRATION_TIME * 60);
        $value = Hash::encrypt($value);
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }

    public static function remove($name)
    {
        return setcookie($name, '', time() - 3600, '/');
    }
}
