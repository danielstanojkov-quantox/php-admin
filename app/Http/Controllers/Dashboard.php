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

    $data = $this->getSidebarData();
    $data['table_contents'] = $this->getTableContent();

    $this->view('dashboard/main', $data);
  }

  /**
   * Fetches all data related to the sidebar
   *
   * @return array
   */
  protected function getSidebarData(): array
  {
    $db = Database::getInstance();

    try {
      $tables = $db->getTables();
    } catch (\Throwable $th) {
      session('db_err', $th->getMessage());
      Redirect::To('/dashboard');
    }

    $data =  [
      'host' => Auth::host(),
      'username' => Auth::username(),
      'databases' => $db->allDatabaseNames(),
      'tables' => $tables
    ];

    return $data;
  }

  /**
   * Fetches content for a specific table
   *
   * @return mixed
   */
  protected function getTableContent(): mixed
  {
    $db = Database::getInstance();

    if (!Request::has('table')) {
      return null;
    }

    try {
      return  $db->fetchTableContents(
        Request::input('db_name'),
        Request::input('table')
      );
    } catch (\Throwable $th) {
      session('db_err', $th->getMessage());
      Redirect::To('/dashboard');
    }
  }
}
