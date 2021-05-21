<?php

namespace App\Http\Middleware;

use App\Helpers\Cookie;

class IsAuthenticatedMiddleware
{
    /**
     *
     * @var Cookie $cookie
     */
    private $cookie;

    /**
     *
     * @param Cookie $cookie
     */
    public function __construct(Cookie $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * Allow access if the user is authenticated
     *
     * @param  $request
     * @return bool
     */
    public function handle(): bool
    {
        return $this->cookie->exists('user_id');
    }
}
