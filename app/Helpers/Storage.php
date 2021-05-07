<?php

namespace App\Helpers;

class Storage
{
    /**
     * Adds an user to users.json file
     *
     * @param array $user
     * @return void
     */
    public static function add(array $user): void
    {
        if (!File::exists(USERS)) File::createStorageFolder();

        $users = static::getUsers() ?? [];
        array_push($users, $user);

        static::setUsers($users);
    }

    /**
     * Removes user by id from the users.json file
     *
     * @param string $id
     * @return void
     */
    public static function removeUserById($id): void
    {
        $users = static::getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return $user->id != $id;
        });

        static::setUsers($users);
    }

    /**
     * Fetches user by id from users.json file
     *
     * @param string $id
     * @return mixed
     */
    public static function getUserById($id): mixed
    {
        $users = static::getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return $user->id = $id;
        });

        return $users[0] ?? null;
    }

    /**
     * Retrieve all users from user.json
     *
     * @return array
     */
    public static function getUsers(): array
    {
        return json_decode(
            Hash::decrypt(File::get(USERS))
        );
    }

    /**
     * Creates brand new data in users.json
     *
     * @param array $users
     * @return void
     */
    public static function setUsers(array $users): void
    {
        File::put(
            USERS,
            Hash::encrypt(json_encode($users))
        );
    }
}
