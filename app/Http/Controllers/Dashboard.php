<?php

namespace App\Http\Controllers;

use App\Helpers\Redirect;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;

class Dashboard extends Controller
{

  public function index()
  {
    if (!IsAuthenticatedMiddleware::handle()) Redirect::To('/login');

    $data = [];

    $this->view('dashboard/index', $data);
  }
}
