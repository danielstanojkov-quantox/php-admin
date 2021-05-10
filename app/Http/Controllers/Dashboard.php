<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;
use App\Libraries\Database;

class Dashboard extends Controller
{
  /**
   * Displays dashboard view to user
   *
   * @return void
   */
  public function index(): void
  {
    if (!IsAuthenticatedMiddleware::handle()) Redirect::To('/login');

    $data = [
      'host' => Auth::host(),
      'username' => Auth::username(),
      'databases' => Database::all(),
      'tables' => Database::getTables()
    ];

    $this->view('dashboard/index', $data);
  }
}
