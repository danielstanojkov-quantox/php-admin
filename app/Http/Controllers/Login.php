<?php

namespace App\Http\Controllers;

use App\Libraries\Controller;
use App\Libraries\Database;

class Login extends Controller
{
    public function index()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->login();
            return;
        }

        $data = [];
        $this->view('auth/login', $data);
    }

    public function login()
    {
        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];
     
        new Database($host, $username, $password);
    }
}
