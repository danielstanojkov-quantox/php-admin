<?php

namespace App\Helpers;

use Carbon\Carbon;

class Cookie
{
    /**
     * Returns the value of a cookie
     *
     * @param string $name
     * @return string
     */
    public static function get($name): string
    {
        return Hash::decrypt($_COOKIE[$name]);
    }

    /**
     * Set a cookie
     *
     * @param string $name
     * @param string $value
     * @param integer $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return void
     */
    public static function set(
        $name,
        $value = '',
        $expires = null,
        $path = '/',
        $domain = '',
        $secure = false,
        $httponly = false
    ): void {
        $expires = Carbon::now()->timestamp + (EXPIRATION_TIME * 60);
        $value = Hash::encrypt($value);
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    /**
     * Checks if cookie exist
     *
     * @param string $name
     * @return bool
     */
    public static function exists($name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Delete cookie
     *
     * @param string $name
     * @return bool
     */
    public static function remove($name): bool
    {
        return setcookie($name, '', time() - 3600, '/');
    }
}
