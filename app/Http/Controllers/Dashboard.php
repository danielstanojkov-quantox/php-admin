<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Log;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Helpers\Session;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;
use App\Libraries\Database;


class Dashboard extends Controller
{
  /**
   * @var Redirect $redirect;
   */
  private $redirect;

  /**
   * @var Request $request;
   */
  private $request;

  /**
   * @var Auth $auth;
   */
  private $auth;

  /**
   * @var Log $logger;
   */
  private $logger;

  /**
   * @var Session $session;
   */
  private $session;

  /**
   * @var Database $database;
   */
  private $database;

  /**
   * Dashboard Constructor
   *
   * @param Redirect $redirect
   * @param Request $request
   * @param Auth $auth
   * @param Log $logger
   * @param Session $session
   * @param Database $database
   */
  public function __construct(
    Redirect $redirect,
    Request $request,
    Auth $auth,
    Log $logger,
    Session $session,
    Database $database
  ) {
    $this->redirect = $redirect;
    $this->request = $request;
    $this->auth = $auth;
    $this->logger = $logger;
    $this->session = $session;
    $this->database = $database;
  }

  /**
   * Displays dashboard view to user
   *
   * @return void
   */
  public function index(): void
  {
    if (!IsAuthenticatedMiddleware::handle()) {
      $this->redirect->to('/login');
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
    try {
      $tables = $this->database->getTables();
    } catch (\Throwable $th) {

      session('db_error', $th->getMessage());
      $this->redirect->to('/dashboard');
    }

    $data =  [
      'host' => $this->auth->host(),
      'username' => $this->auth->username(),
      'databases' => $this->database->allDatabaseNames(),
      'tables' => $tables,
      'accounts' => $this->getUsers()
    ];

    return $data;
  }

  /**
   * Retrieves all users from server
   *
   * @return array
   */
  public function getUsers(): array
  {
    try {
      $users = $this->database->getAccounts();
    } catch (\Throwable $th) {
      $users = [];
    }
    return $users;
  }

  /**
   * Fetches content for a specific table
   *
   * @return mixed
   */
  protected function getTableContent(): mixed
  {
    if (!$this->request->has('table')) {
      return null;
    }

    try {
      return $this->database->fetchTableContents(
        $this->request->input('db_name'),
        $this->request->input('table')
      );
    } catch (\Throwable $th) {
      session('db_error', $th->getMessage());
      $this->redirect->to('/dashboard');
    }
  }

  /**
   * Get all Character Sets with related Collations
   *
   * @return array
   */
  public function getEncodingTypes(): array
  {
    $collations = $this->database->getCollations();

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
    if ($this->request->isGet()) {
      $this->redirect->to('/dashboard');
      return;
    }

    $encoding = $this->request->input('encodingType');
    $encodingTypesArray = explode(':', $encoding);

    $dbName = $this->request->input('dbName');
    $charset = $encodingTypesArray[0];
    $collation = $encodingTypesArray[1];

    try {
      $this->database->createDatabase($dbName, $charset, $collation);
      $this->session->flash('db_creation_success', 'Database created successfully');
      $this->logger->info("Database $dbName created successfully");
      $this->redirect->to('/dashboard');
    } catch (\Throwable $th) {
      $this->session->flash('db_creation_error', $th->getMessage());
      $this->session->flash('dbName', $dbName);
      $this->logger->error($th->getMessage());
      $this->redirect->to('/dashboard');
    }
  }
}
