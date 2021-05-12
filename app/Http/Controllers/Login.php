<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Helpers\Session;
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
        if (IsAuthenticatedMiddleware::handle()) {
            Redirect::to('/dashboard');
        }

        if (Request::isPost()) {
            return $this->login('a');
        }

        return $this->view('auth/login');
    }

    /**
     * Authenticate user
     *
     * @return void
     */
    public function login(): void
    {
        $credentials = Request::all();

        try {
            Database::getInstance($credentials);
        } catch (\Throwable $e) {

            Session::flash('login_failed', $e->getMessage());
            Session::flash('host', $credentials['host']);
            Session::flash('username', $credentials['username']);

            Redirect::to('/login');
        }

        $user = new User($credentials);
        $user = $user->save();

        Cookie::set('user_id', $user['id']);
        Redirect::to('/dashboard');
    }
}
