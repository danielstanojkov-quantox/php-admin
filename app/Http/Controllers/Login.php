<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Log;
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
     * @var Redirect $redirect;
     */
    private $redirect;

    /**
     * @var Request $request;
     */
    private $request;

    /**
     * @var Log $logger;
     */
    private $logger;

    /**
     * @var Session $session;
     */
    private $session;

    /**
     * @var Cookie $cookie;
     */
    private $cookie;

    /**
     * @var Database $database;
     */
    private $database;

    /**
     * @var User $user;
     */
    private $user;

    /**
     * Login Constructor
     *
     * @param Redirect $redirect
     * @param Request $request
     * @param Log $logger
     * @param Session $session
     * @param Cookie $cookie
     * @param Database $database
     * @param User $user
     */
    public function __construct(
        Redirect $redirect,
        Request $request,
        Log $logger,
        Session $session,
        Cookie $cookie,
        Database $database,
        User $user
    ) {
        $this->redirect = $redirect;
        $this->request = $request;
        $this->logger = $logger;
        $this->session = $session;
        $this->cookie = $cookie;
        $this->database = $database;
        $this->user = $user;
    }

    /**
     * Displays login view
     *
     * @return mixed
     */
    public function index(): mixed
    {
        if (IsAuthenticatedMiddleware::handle()) {
            $this->redirect->to('/dashboard');
        }

        if ($this->request->isPost()) {
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
        $credentials = $this->request->all();

        try {
            $this->database->connect($credentials);
            $this->logger->info("User " . $credentials['username'] . " has been logged in.");
        } catch (\Throwable $e) {

            $this->session->flash('login_failed', $e->getMessage());
            $this->session->flash('host', $credentials['host']);
            $this->session->flash('username', $credentials['username']);

            $this->logger->error($e->getMessage());
            $this->redirect->to('/login');
        }

        $user = $this->user->save($credentials);

        $this->cookie->set('user_id', $user['id']);
        $this->redirect->to('/dashboard');
    }
}
