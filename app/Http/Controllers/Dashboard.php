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
      'tables' => $this->getTables()
    ];

    $this->view('dashboard/index', $data);
  }

  /**
   * Get tables for specified database
   *
   * @return void
   */
  private function getTables()
  {
    $db = Database::getInstance();

    $tables = [];
    if (Request::input('db_name')) {
      try {
        $tables = $db->getTables(Request::input('db_name'));
      } catch (\Throwable $th) {
        session('db_not_found', "Database doesn't exist");
        Redirect::To('/dashboard');
      }
    } else {
      return null;
    }

    $tables = array_map(function ($table) {
      $table = array_values($table);
      return array_pop($table);
    }, $tables);

    return $tables;
  }
}
