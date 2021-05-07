<?php

namespace App\Helpers;

class Auth 
{
    public static function host()
    {
        $user = static::getUser();
        return $user->host;
    }

    public static function username()
    {
        $user = static::getUser();
        return $user->username;
    }

    protected static function getUser()
    {
        return Storage::getUserById(Cookie::get('user_id'));
    }
}