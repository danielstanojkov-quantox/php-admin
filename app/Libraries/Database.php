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
  public static function getInstance(array $credentials = null): object
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
   * @return array
   */
  public function getTables($databaseName): array
  {
    try {
      $stmt = self::$pdo->query("SHOW TABLES FROM $databaseName;");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * Retrieves table data
   *
   */
  public function fetchTableContents($database, $tableName)
  {
    $stmt = self::$pdo->query("USE $database;");
    $stmt->execute();
    $stmt = self::$pdo->query("SELECT * FROM $tableName;");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }




  // /**
  //  * Prepare statement with query
  //  *
  //  * @param string $sql
  //  * @return void
  //  */
  // public function query($sql): void
  // {
  //   var_dump(static::$instance);
  //   die;
  //   // static::$stmt = self::$instance->prepare($sql);
  // }
  // /**
  //  * Bind values
  //  *
  //  * @param string $param
  //  * @param string $value
  //  * @param string $type
  //  * @return void
  //  */
  // public function bind($param, $value, $type = null): void
  // {
  //   if (is_null($type)) {
  //     switch (true) {
  //       case is_int($value):
  //         $type = PDO::PARAM_INT;
  //         break;
  //       case is_bool($value):
  //         $type = PDO::PARAM_BOOL;
  //         break;
  //       case is_null($value):
  //         $type = PDO::PARAM_NULL;
  //         break;
  //       default:
  //         $type = PDO::PARAM_STR;
  //     }
  //   }

  //   $this->stmt->bindValue($param, $value, $type);
  // }

  // /**
  //  * Execute the prepared statement
  //  *
  //  * @return bool
  //  */
  // public function execute(): bool
  // {
  //   return $this->stmt->execute();
  // }

  // /**
  //  * Get result set as array of objects
  //  *
  //  * @return bool
  //  */
  // public function resultSet(): bool
  // {
  //   $this->execute();
  //   return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  // }

  // /**
  //  * Get single record as object
  //  *
  //  * @return bool
  //  */
  // public function single(): bool
  // {
  //   $this->execute();
  //   return $this->stmt->fetch(PDO::FETCH_OBJ);
  // }

  // /**
  //  * Get row count
  //  *
  //  * @return int
  //  */
  // public function rowCount(): int
  // {
  //   return $this->stmt->rowCount();
  // }
}
