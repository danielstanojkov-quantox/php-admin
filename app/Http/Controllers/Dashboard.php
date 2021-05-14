<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Helpers\Session;
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
    $data['encoding_types'] = $this->getEncodingTypes();

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

  /**
   * Get all Character Sets with related Collations
   *
   * @return array
   */
  public function getEncodingTypes(): array
  {
    $db = Database::getInstance();
    $collations = $db->getCollations();

    $encodingTypes = [];

    foreach ($collations as $collation) {
      $charset = $collation['Charset'];
      $collation = $collation['Collation'];

      if (!array_key_exists($charset, $encodingTypes)) {
        $encodingTypes[$charset] = [];
      }

      array_push($encodingTypes[$charset], $collation);
    }

    ksort($encodingTypes, SORT_REGULAR);

    return $encodingTypes;
  }

  /**
   * Create Database to Server
   *
   * @return void
   */
  public function store(): void
  {
    if (Request::isGet()) {
      Redirect::to('/dashboard');
      return;
    }

    $encoding = Request::input('encodingType');
    $encodingTypesArray = explode(':', $encoding);

    $dbName = Request::input('dbName');
    $charset = $encodingTypesArray[0];
    $collation = $encodingTypesArray[1];

    $db = Database::getInstance();

    try {
      $db->createDatabase($dbName, $charset, $collation);
      Session::flash('db_creation_success', 'Database created successfully');
      Redirect::to('/dashboard');
    } catch (\Throwable $th) {
      Session::flash('db_creation_error', $th->getMessage());
      Session::flash('dbName', $dbName);
      Redirect::to('/dashboard');
    }
  }
}
