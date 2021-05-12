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
    public static function get(string $name): string
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
        string $name,
        string $value = '',
        int $expires = null,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = false
    ): void {
        $expires = Carbon::now()->timestamp + (app('EXPIRATION_TIME') * 60);
        $value = Hash::encrypt($value);
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    /**
     * Checks if cookie exist
     *
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Delete cookie
     *
     * @param string $name
     * @return bool
     */
    public static function remove(string $name): bool
    {
        return setcookie($name, '', time() - 3600, '/');
    }
}
