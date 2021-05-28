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
     * @var Redirect $redirect;
     */
    private $redirect;

    /**
     * @var Session $session;
     */
    private $session;

    /**
     * @var Log $logger;
     */
    private $logger;

    /**
     * @var Database $database;
     */
    private $database;

    /**
     * @var CreateUsersRequest $usersRequest;
     */
    private $usersRequest;

    /**
     * Users Constructor
     *
     * @param Redirect $redirect
     * @param Session $session
     * @param Log $logger
     * @param Database $database
     * @param CreateUsersRequest $usersRequest
     */
    public function __construct(
        Redirect $redirect,
        Session $session,
        Log $logger,
        Database $database,
        CreateUserRequest $usersRequest
    ) {
        $this->redirect = $redirect;
        $this->session = $session;
        $this->logger = $logger;
        $this->database = $database;
        $this->usersRequest = $usersRequest;
    }

    /**
     * Handles creating new user accounts
     *
     * @return void
     */
    public function store(Request $request): void
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $role = $request->input('role');

        $dbName = $request->input('db_name');
        $uri = $dbName ? "/dashboard?db_name=$dbName" : '/dashboard';

        if (!$this->usersRequest->validate($role, $username)) {
            $this->session->flash('account_username', $username);
            $this->redirect->to($uri);
        }

        try {
            $this->database->createUser(app('host'), $username, $password, $role);
            $this->logger->info("Account $username@" . app('host') . " created successfuly");
            $this->session->flash('registration_successfull', 'User has been created successfully');
        } catch (\Throwable $th) {
            $this->session->flash('registration_failed', $th->getMessage());
            $this->logger->error($th->getMessage());
        }

        $this->redirect->to($uri);
    }

    /**
     * Delete User Account
     *
     * @return void
     */
    public function delete(Request $request)
    {
        $account = $request->input('account');

        try {
            $this->database->deleteUser($account);
            $this->session->flash('user_deleted_success', 'User account has been removed successfully');
            $this->logger->info("Account $account deleted successfuly");
        } catch (\Throwable $th) {
            $this->session->flash('user_deleted_error', $th->getMessage());
            $this->logger->error($th->getMessage());
        }

        $this->redirect->to('/dashboard');
    }
}
