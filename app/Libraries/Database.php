<?php

namespace App\Libraries;

use App\Exceptions\AuthException;
use App\Helpers\Cookie;
use App\Helpers\Request;
use App\Helpers\UserStorage;
use \Pdo;
use PDOStatement;

class Database
{
  /**
   * Database Connection
   *
   * @var Pdo
   */
  public $pdo;

  /**
   * PDO statement
   *
   * @var PDOStatement
   */
  public static $stmt;

  /**
   * PDO Error
   *
   * @var PDOError
   */
  public static $error;

  /**
   * @var Cookie
   */
  public $cookie;

  /**
   * @var UserStorage
   */
  public $storage;

  /**
   * @var Request
   */
  public $request;

  /**
   * Database Constructor
   *
   * @param Cookie $cookie
   * @param UserStorage $storage
   * @param Request $request
   */
  public function __construct(Cookie $cookie, UserStorage $storage, Request $request)
  {
    $this->cookie = $cookie;
    $this->storage = $storage;
    $this->request = $request;
    $this->connect();
  }

  public function connect($credentials = null)
  {
    if (!$credentials && !$this->cookie->exists('user_id')) return;

    if ($this->cookie->exists('user_id')) {
      $user = $this->storage->getUserById($this->cookie->get('user_id'));
      $credentials = (array) $user;
    }

    $dsn = "mysql:host=" . $credentials['host'];
    $options = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
      $this->pdo = new PDO($dsn, $credentials['username'], $credentials['password'], $options);
    } catch (\Throwable $th) {
      throw new AuthException($th->getMessage());
    }
  }

  /**
   * Retrieves all database names from server
   *
   * @return array
   */
  public function allDatabaseNames(): array
  {
    $stmt = $this->pdo->query('SHOW DATABASES;');
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Retrieves all table names for specified database
   *
   * @param string $databaseName
   * @return array
   */
  public function getTablesFromServer(string $databaseName): array
  {
    $statement = $this->pdo->query("SHOW TABLES FROM $databaseName;");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get tables for specified database
   *
   * @return mixed
   */
  public function getTables(): mixed
  {
    if (!$this->request->input('db_name')) {
      return null;
    }

    $tables = $this->getTablesFromServer($this->request->input('db_name'));

    $tables = array_map(function ($table) {
      $table = array_values($table);
      return array_pop($table);
    }, $tables);

    return $tables;
  }

  /**
   * Retrieve all Character Sets
   *
   * @return array
   */
  public function getCharsets(): array
  {
    $statement = $this->pdo->query("SHOW CHARSET");
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Retrieve all collations
   *
   * @return array
   */
  public function getCollations(): array
  {
    $statement = $this->pdo->query("SHOW COLLATION;");
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Create new database on server
   *
   * @param string $dbName
   * @param string $charset
   * @param string $collation
   * @return void
   */
  public function createDatabase(string $dbName, string $charset, string $collation): void
  {
    $sql = "CREATE DATABASE $dbName CHARACTER SET $charset COLLATE $collation";
    $this->pdo->query($sql);
  }

  /**
   * Retrieves table data
   *  @param string $database
   *  @param string $tableName
   *  @return mixed
   */
  public function fetchTableContents(string $database, string $tableName): mixed
  {
    $statement = $this->pdo->query("USE $database;");
    $statement->execute();
    $statement = $this->pdo->query("SELECT * FROM $tableName;");

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Execute sql query on database
   *
   * @param string $database
   * @param string $sql
   * @return array
   */
  public function sql(string $database, string $sql): array
  {
    $statement = $this->pdo->query("USE $database;");
    $statement->execute();
    $statement = $this->pdo->query($sql);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Import Database
   *
   * @param string $database
   * @param string $sql
   * @return void
   */
  public function import(string $database, string $sql): void
  {
    $statement = $this->pdo->query("USE $database;");
    $statement->execute();
    $statement = $this->pdo->query($sql);
  }

  /**
   * Get all user accounts from server
   *
   * @return array
   */
  public function getAccounts(): array
  {
    $statement = $this->pdo->query("SELECT user, host, authentication_string, Grant_priv FROM mysql.user;");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Create user
   *
   * @param string $host
   * @param string $username
   * @param string $password
   * @param string $role
   * @return void
   */
  public function createUser(string $host, string $username, string $password, string $role): void
  {
    $sql = "CREATE USER '$username'@'$host'";
    if (!empty($password)) {
      $sql = $sql . " IDENTIFIED BY '$password'";
    }

    $this->pdo->query($sql);

    switch ($role) {
      case 'admin':
        $this->pdo->query("GRANT ALL PRIVILEGES ON *.* TO $username@$host WITH GRANT OPTION;");
        break;

      case 'maintainer':
        $this->pdo->query("GRANT SELECT,INSERT,UPDATE,DELETE ON *.* TO $username@$host;");
        break;

      case 'basic':
        $this->pdo->query("GRANT SELECT ON *.* TO $username@$host;");
        break;
    }
  }

  /**
   * Delete User account from the server
   *
   * @param string $user
   * @return void
   */
  public function deleteUser(string $user): void
  {
    $this->pdo->query("DROP USER $user;");
  }
}
