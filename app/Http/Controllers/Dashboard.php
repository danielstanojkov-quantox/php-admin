<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Helpers\Request;
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
    if (!IsAuthenticatedMiddleware::handle()) {
      Redirect::to('/login');
    }

    $db = Database::getInstance();

    $data =  [
      'host' => Auth::host(),
      'username' => Auth::username(),
      'databases' => $db->allDatabaseNames(),
      'tables' => $db->getTables()
    ];

    if (Request::has('table')) {
      $data['table_contents'] = $db->fetchTableContents(
        Request::input('db_name'),
        Request::input('table')
      );
    }

    $this->view('dashboard/main', $data);
  }
}
