<?php

namespace App\Helpers;

class Auth
{
    /**
     *
     * @var UserStorage $storage
     */
    private $storage;

    /**
     *
     * @var Cookie $cookie
     */
    private $cookie;

    /**
     * Undocumented function
     *
     * @param UserStorage $storage
     * @param Cookie $cookie
     */
    public function __construct(UserStorage $storage, Cookie $cookie)
    {
        $this->storage = $storage;
        $this->cookie = $cookie;
    }

    /**
     *  Authenticated user's host
     *
     * @return string
     */
    public function host(): string
    {
        return $this->getUser()->host;
    }

    /**
     *  Authenticated user's username
     *
     * @return string
     */
    public function username(): string
    {
        return $this->getUser()->username;
    }

    /**
     *  Authenticated user
     *
     * @return object
     */
    protected function getUser(): object
    {
        return $this->storage->getUserById(
            $this->cookie->get('user_id')
        );
    }
}
