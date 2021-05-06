<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Libraries\Controller;
use App\Libraries\Database;
use App\Models\User;
use App\Http\Middleware\IsAuthenticatedMiddleware;

class Login extends Controller
{
    public function index()
    {
        if (IsAuthenticatedMiddleware::handle()) Redirect::To('/dashboard');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

        $user = new User($host, $username, $password);
        $user = $user->save();
        
        Cookie::set('user_id', $user['id']);

        Redirect::To('/dashboard');
    }
}
