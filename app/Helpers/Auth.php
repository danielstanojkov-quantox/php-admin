<?php

namespace App\Helpers;

class Auth
{
    /**
     *  Authenticated user's host
     *
     * @return string
     */
    public static function host(): string
    {
        $user = static::getUser();
        return $user->host;
    }

    /**
     *  Authenticated user's username
     *
     * @return string
     */
    public static function username(): string
    {
        $user = static::getUser();
        return $user->username;
    }

    /**
     *  Authenticated user
     *
     * @return object
     */
    protected static function getUser(): object
    {
        return UserStorage::getUserById(Cookie::get('user_id'));
    }
}
