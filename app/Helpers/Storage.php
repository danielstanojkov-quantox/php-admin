<?php

namespace App\Helpers;

class Storage
{
    public static function add($user)
    {
        if (!File::exists(USERS)) File::createStorageFolder();

        $users = static::getUsers() ?? [];
        array_push($users, $user);

        static::setUsers($users);
    }

    public static function getUsers()
    {
        return json_decode(
            File::get(USERS)
        );
    }

    public static function setUsers($users)
    {
        File::put(
            USERS,
            json_encode($users)
        );
    }
}
