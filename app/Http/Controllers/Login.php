<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\Server;
use App\Libraries\Controller;
use App\Libraries\Database;
use App\Models\User;
use App\Http\Middleware\IsAuthenticatedMiddleware;

class Login extends Controller
{
    /**
     * Displays login view
     *
     * @return mixed
     */
    public function index(): mixed
    {
        if (IsAuthenticatedMiddleware::handle())
            Redirect::to('/dashboard');

        if (Server::requestIs('post'))
            return $this->login();

        return $this->view('auth/login', $data = []);
    }

    /**
     * Authenticate user
     *
     * @return void
     */
    public function login(): void
    {
        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        Database::connect($host, $username, $password);

        $user = new User($host, $username, $password);
        $user = $user->save();

        Cookie::set('user_id', $user['id']);
        Redirect::to('/dashboard');
    }
}
