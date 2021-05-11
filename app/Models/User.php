<?php

namespace App\Models;

use App\Helpers\UserStorage;
use App\Libraries\Controller;
use Carbon\Carbon;

class User extends Controller
{
    /**
     * User's Host
     *
     * @var string
     */
    private $host;

    /**
     * Authenticated user's username
     *
     * @var string
     */
    private $username;

    /**
     * Authenticated user's password
     *
     * @var string
     */
    private $password;

    /**
     * User Constructor
     *
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->host = $credentials['host'];
        $this->username = $credentials['username'];
        $this->password = $credentials['password'];
    }

    /**
     * Creates a new user
     *
     * @return array
     */
    public function save(): array
    {
        $user = [
            'id' => Carbon::now()->timestamp,
            'host' => $this->host,
            'username' => $this->username,
            'password' => $this->password
        ];

        UserStorage::add($user);

        return $user;
    }
}
