<?php

namespace App\Libraries;

use App\Helpers\Cookie;
use App\Helpers\Request;
use App\Helpers\UserStorage;
use \Pdo;
use \PDOException;
use PDOStatement;

class Database
{
  /**
   * Database Instance
   *
   */
  private static $instance = null;

  /**
   * Database Connection
   *
   * @var Pdo
   */
  public static $pdo;

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
   * Database constructor
   *
   */
  private function __construct($credentials)
  {
    if (Cookie::exists('user_id')) {
      $user = UserStorage::getUserById(Cookie::get('user_id'));
      $credentials = (array) $user;
    }

    $dsn = "mysql:host=" . $credentials['host'];
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      self::$pdo = new PDO($dsn, $credentials['username'], $credentials['password'], $options);
    } catch (PDOException $e) {
      throw $e;
    }
  }

  /**
   * Get connection instance
   *
   * @return array
   */
  public static function getInstance(array $credentials = []): object
  {
    if (self::$instance == null) {
      self::$instance = new Database($credentials);
    }

    return self::$instance;
  }

  /**
   * Retrieves all database names from server
   *
   * @return array
   */
  public function allDatabaseNames(): array
  {
    $stmt = self::$pdo->query('SHOW DATABASES;');
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
    try {
      $statement = self::$pdo->query("SHOW TABLES FROM $databaseName;");
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * Get tables for specified database
   *
   * @return mixed
   */
  public function getTables(): mixed
  {
    $db = Database::getInstance();

    if (!Request::input('db_name')) {
      return null;
    }

    try {
      $tables = $db->getTablesFromServer(Request::input('db_name'));

      $tables = array_map(function ($table) {
        $table = array_values($table);
        return array_pop($table);
      }, $tables);

      return $tables;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * Retrieve all Character Sets
   *
   * @return array
   */
  public function getCharsets(): array
  {
    $statement = self::$pdo->query("SHOW CHARSET");
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
    $statement = self::$pdo->query("SHOW COLLATION;");
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

    try {
      self::$pdo->query($sql);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * Retrieves table data
   *  @param string $database
   *  @param string $tableName
   *  @return mixed
   */
  public function fetchTableContents(string $database, string $tableName): mixed
  {
    $statement = self::$pdo->query("USE $database;");
    $statement->execute();

    try {
      $statement = self::$pdo->query("SELECT * FROM $tableName;");
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      throw $th;
    }
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
    $statement = self::$pdo->query("USE $database;");
    $statement->execute();

    try {
      $statement = self::$pdo->query($sql);
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      throw $th;
    }
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
    $stmt = self::$pdo->query("USE $database;");
    $stmt->execute();

    try {
      $stmt = self::$pdo->query($sql);
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
