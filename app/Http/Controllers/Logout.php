<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\UserStorage;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;

class Logout extends Controller
{
    /**
     * @var Redirect $redirect;
     */
    private $redirect;

    /**
     * @var UserStorage $storage;
     */
    private $storage;

    /**
     * @var Cookie $cookie;
     */
    private $cookie;

    /**
     * Dashboard Constructor
     *
     * @param Redirect $redirect
     * @param UserStorage $storage
     * @param Cookie $cookie
     */
    public function __construct(Redirect $redirect, UserStorage $storage, Cookie $cookie)
    {
        $this->redirect = $redirect;
        $this->storage = $storage;
        $this->cookie = $cookie;
    }

    /**
     * Logout the authenticated user
     *
     * @return void
     */
    public function index(): void
    {
        if (IsAuthenticatedMiddleware::handle()) {
            $this->storage->removeUserById($this->cookie->get('user_id'));
            $this->cookie->remove('user_id');
        }

        $this->redirect->to('/login');
    }
}
