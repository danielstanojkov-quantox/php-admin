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

    public static function removeUserById($id)
    {
        $users = static::getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return $user->id != $id;
        });

        static::setUsers($users);
    }

    public static function getUsers()
    {
        return json_decode(
            Hash::decrypt(File::get(USERS))
        );
    }

    public static function setUsers($users)
    {
        File::put(
            USERS,
            Hash::encrypt(json_encode($users))
        );
    }
}
