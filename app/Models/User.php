<?php

namespace App\Models;

use App\Helpers\UserStorage;
use Carbon\Carbon;

class User
{
    /**     
     * @var UserStorage $storage
     */
    public $storage;

    /**
     * User Constructor
     *
     * @param UserStorage $storage
     */
    public function __construct(UserStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Creates a new user
     * @param array $credentials
     * @return array
     */
    public function save(array $credentials): array
    {
        $user = [
            'id' => Carbon::now()->timestamp,
            'host' => $credentials['host'],
            'username' => $credentials['username'],
            'password' => $credentials['password']
        ];

        $this->storage->add($user);
        return $user;
    }
}
