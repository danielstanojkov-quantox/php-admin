<?php

namespace App\Models;

use App\Helpers\Storage;
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
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
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

        Storage::add($user);

        return $user;
    }
}
