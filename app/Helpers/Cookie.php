<?php

namespace App\Helpers;

use Carbon\Carbon;

class Cookie
{
    /**
     *
     * @var Hash $hash
     */
    private $hash;

    /**
     * Cookie Constructor
     *
     * @param Hash $hash
     */
    public function __construct(Hash $hash)
    {
        $this->hash = $hash;
    }

    /**
     * Returns the value of a cookie
     *
     * @param string $name
     * @return string
     */
    public function get(string $name): string
    {
        return $this->hash->decrypt($_COOKIE[$name]);
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
    public function set(
        string $name,
        string $value = '',
        int $expires = null,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = false
    ): void {
        $expires = Carbon::now()->timestamp + (app('expiration_time') * 60);
        $value = $this->hash->encrypt($value);
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }

    /**
     * Checks if cookie exist
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Delete cookie
     *
     * @param string $name
     * @return bool
     */
    public function remove(string $name): bool
    {
        return setcookie($name, '', time() - 3600, '/');
    }
}
