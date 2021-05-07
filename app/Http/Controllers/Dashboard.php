<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;
use App\Libraries\Database;

class Dashboard extends Controller
{

  public function index()
  {
    if (!IsAuthenticatedMiddleware::handle()) Redirect::To('/login');

    $data = [
      'host' => Auth::host(),
      'username' => Auth::username(),
      'databases' => Database::all(),
      'tables' => $this->getTables()
    ];

    $this->view('dashboard/index', $data);
  }


  protected function getTables()
  {
    if (!isset($_GET['db_name'])) return null;
    $tables = Database::tables($_GET['db_name']);

    $tables = array_map(function($table){
      $table = array_values($table);
      return array_pop($table);
    }, $tables);

    return $tables;
  }
}
