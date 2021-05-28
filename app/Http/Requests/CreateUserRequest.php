<?php

namespace App\Http\Requests;

use App\Helpers\Session;

class CreateUserRequest
{
    /**
     *
     * @var Session $session
     */
    public $session;

    /**
     * CreateUserRequest Constructor
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    /**
     * Allowed user roles
     *
     * @var array
     */
    public $roles = ['admin', 'maintainer', 'basic'];

    /**
     * Validate user
     *
     * @param mixed $role
     * @param string $username
     * @return bool 
     */
    public function validate(mixed $role, string $username): bool
    {
        if (empty(trim($username))) {
            $this->session->flash('registration_failed', 'Please enter your username.');
            return false;
        }

        if (!$role) {
            $this->session->flash('registration_failed', 'Please select a role');
            return false;
        }

        if (!in_array($role, $this->roles)) {
            $this->session->flash('registration_failed', 'Invalid role selected');
            return false;
        }

        return true;
    }
}
