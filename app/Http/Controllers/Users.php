<?php

namespace App\Http\Controllers;

use App\Helpers\Log;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Helpers\Session;
use App\Http\Requests\CreateUserRequest;
use App\Libraries\Controller;
use App\Libraries\Database;

class Users extends Controller
{
    /**
     * Handles creating new user accounts
     *
     * @return void
     */
    public function store(): void
    {
        $username = Request::input('username');
        $password = Request::input('password');
        $role = Request::input('role');

        $dbName = Request::input('db_name');
        $uri = $dbName ? "/dashboard?db_name=$dbName" : '/dashboard';

        if (!CreateUserRequest::validate($role, $username)) {
            Session::flash('account_username', $username);
            Redirect::to($uri);
        }

        $db = Database::getInstance();

        try {
            $db->createUser(app('host'), $username, $password, $role);
            Session::flash('registration_successfull', 'User has been created successfully');
        } catch (\Throwable $th) {
            Session::flash('registration_failed', $th->getMessage());
            Log::error($th->getMessage());
        }

        Redirect::to($uri);
    }

    /**
     * Delete User Account
     *
     * @return void
     */
    public function delete()
    {
        $account = Request::input('account');

        $db = Database::getInstance();

        try {
            $db->deleteUser($account);
            Session::flash('user_deleted_success', 'User account has been removed successfully');
        } catch (\Throwable $th) {
            Session::flash('user_deleted_error', $th->getMessage());
            Log::error($th->getMessage());
        }

        Redirect::to('/dashboard');
    }
}
