<?php

namespace App\Helpers;

class UserStorage
{
    /**
     * Creates storage folder for users on server
     *
     * @return void
     */
    public static function makeStorageFolder(): void
    {
        File::makeDirectory('storage');
        File::makeFile('storage/users.json', '');
    }

    /**
     * Adds an user to users.json file
     *
     * @param array $user
     * @return void
     */
    public static function add(array $user): void
    {
        if (!File::exists(app('users'))) {
            static::makeStorageFolder();
        }

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
    public static function removeUserById(string $id): void
    {
        $users = static::getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return $user->id !== (int) $id;
        });

        static::setUsers($users);
    }

    /**
     * Fetches user by id from users.json file
     *
     * @param string $id
     * @return mixed
     */
    public static function getUserById(string $id): mixed
    {
        $users = static::getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return ((int)$user->id) === ((int) $id);
        });

        return array_pop($users) ?? null;
    }

    /**
     * Retrieve all users from user.json
     *
     * @return mixed
     */
    public static function getUsers(): mixed
    {
        return json_decode(
            Hash::decrypt(File::get(app('users')))
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
            app('users'),
            Hash::encrypt(json_encode($users))
        );
    }
}
