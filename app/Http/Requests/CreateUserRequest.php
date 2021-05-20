<?php

namespace App\Http\Requests;

use App\Helpers\Session;

class CreateUserRequest
{
    /**
     * Allowed user roles
     *
     * @var array
     */
    public static $roles = ['admin', 'maintainer', 'basic'];

    /**
     * Validate user
     *
     * @param mixed $role
     * @param string $username
     * @return bool 
     */
    public static function validate(mixed $role, string $username): bool
    {
        if (empty(trim($username))) {
            Session::flash('registration_failed', 'Please enter your username.');
            return false;
        }

        if (!$role) {
            Session::flash('registration_failed', 'Please select a role');
            return false;
        }

        if (!in_array($role, static::$roles)) {
            Session::flash('registration_failed', 'Invalid role selected');
            return false;
        }

        return true;
    }
}
