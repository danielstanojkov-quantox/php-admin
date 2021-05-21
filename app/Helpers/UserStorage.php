<?php

namespace App\Helpers;

class UserStorage
{
    /**
     *
     * @var File $file
     */
    private $file;

    /**
     *
     * @var Hash $hash
     */
    private $hash;
    /**
     * UserStorage constructor
     *
     * @param File $file
     * @param Hash $hash
     */
    public function __construct(File $file, Hash $hash)
    {
        $this->file = $file;
        $this->hash = $hash;
    }
    /**
     * Creates storage folder for users on server
     *
     * @return void
     */
    public function makeStorageFolder(): void
    {
        $this->file->makeDirectory('storage');
        $this->file->makeFile('storage/users.json', '');
    }

    /**
     * Adds an user to users.json file
     *
     * @param array $user
     * @return void
     */
    public function add(array $user): void
    {
        if (!$this->file->exists(app('users'))) {
            $this->makeStorageFolder();
        }

        $users = $this->getUsers() ?? [];
        array_push($users, $user);

        $this->setUsers($users);
    }

    /**
     * Removes user by id from the users.json file
     *
     * @param string $id
     * @return void
     */
    public function removeUserById(string $id): void
    {
        $users = $this->getUsers();

        $users = array_filter($users, function ($user) use ($id) {
            return $user->id !== (int) $id;
        });

        $this->setUsers($users);
    }

    /**
     * Fetches user by id from users.json file
     *
     * @param string $id
     * @return mixed
     */
    public function getUserById(string $id): mixed
    {
        $users = $this->getUsers();

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
    public function getUsers(): mixed
    {
        return json_decode(
            $this->hash->decrypt($this->file->get(app('users')))
        );
    }

    /**
     * Creates brand new data in users.json
     *
     * @param array $users
     * @return void
     */
    public function setUsers(array $users): void
    {
        $this->file->put(
            app('users'),
            $this->hash->encrypt(json_encode($users))
        );
    }
}
