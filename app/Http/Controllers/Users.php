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
     * @var Request $request;
     */
    private $request;

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
     * Users Constructor
     *
     * @param Request $request
     * @param Redirect $redirect
     * @param Session $session
     * @param Log $logger
     */
    public function __construct(Request $request, Redirect $redirect, Session $session, Log $logger)
    {
        $this->request = $request;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->logger = $logger;
    }

    /**
     * Handles creating new user accounts
     *
     * @return void
     */
    public function store(): void
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $role = $this->request->input('role');

        $dbName = $this->request->input('db_name');
        $uri = $dbName ? "/dashboard?db_name=$dbName" : '/dashboard';

        if (!CreateUserRequest::validate($role, $username)) {
            $this->session->flash('account_username', $username);
            $this->redirect->to($uri);
        }

        $db = Database::getInstance();

        try {
            $db->createUser(app('host'), $username, $password, $role);
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
    public function delete()
    {
        $account = $this->request->input('account');

        $db = Database::getInstance();

        try {
            $db->deleteUser($account);
            $this->session->flash('user_deleted_success', 'User account has been removed successfully');
            $this->logger->info("Account $account deleted successfuly");
        } catch (\Throwable $th) {
            $this->session->flash('user_deleted_error', $th->getMessage());
            $this->logger->error($th->getMessage());
        }

        $this->redirect->to('/dashboard');
    }
}
