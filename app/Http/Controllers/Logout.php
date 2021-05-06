<?php

namespace App\Http\Controllers;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\Storage;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;

class Logout extends Controller
{
    public function index()
    {
        if(IsAuthenticatedMiddleware::handle()){
            Storage::removeUserById(Cookie::get('user_id'));
            Cookie::remove('user_id');
        } 

        Redirect::To('/login');
    }
}
