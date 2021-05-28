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
use App\Exceptions\AuthException;
use Throwable;

class Login extends Controller
{
    /**
     * @var Redirect $redirect;
     */
    private $redirect;

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
     * @param Log $logger
     * @param Session $session
     * @param Cookie $cookie
     * @param Database $database
     * @param User $user
     */
    public function __construct(
        Redirect $redirect,
        Log $logger,
        Session $session,
        Cookie $cookie,
        Database $database,
        User $user
    ) {
        $this->redirect = $redirect;
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
    public function index(Request $request): mixed
    {
        if (IsAuthenticatedMiddleware::handle()) {
            $this->redirect->to('/dashboard');
        }

        if ($request->isPost()) {
            return $this->login($request);
        }

        return $this->view('auth/login');
    }

    /**
     * Authenticate user
     *
     * @return void
     */
    public function login(Request $request): void
    {
        $credentials = $request->all();

        try {
            $this->database->connect($credentials);
            $this->logger->info("User " . $credentials['username'] . " has been logged in.");
        } catch (AuthException $e) {
            $this->session->flash('login_failed', $e->getMessage());
            $this->session->flash('host', $credentials['host']);
            $this->session->flash('username', $credentials['username']);
            $this->redirect->to('/login');
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->redirect->to('/login');
        }

        $user = $this->user->save($credentials);

        $this->cookie->set('user_id', $user['id']);
        $this->redirect->to('/dashboard');
    }
}
