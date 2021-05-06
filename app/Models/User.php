<?php

namespace App\Models;

use App\Helpers\Storage;
use App\Libraries\Controller;
use Carbon\Carbon;

class User extends Controller
{
    private $host;
    private $username;
    private $password;

    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    public function save()
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