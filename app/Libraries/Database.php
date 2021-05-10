<?php

namespace App\Libraries;

use App\Helpers\Cookie;
use App\Helpers\UserStorage;
use \Pdo;
use \PDOException;
use PDOStatement;

class Database
{
  /**
   * Database Connection
   *
   * @var Pdo
   */
  private static $instance = null;

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
    if(Cookie::exists('user_id')){
      $user = UserStorage::getUserById(Cookie::get('user_id'));
      unset($user->id);
      $credentials = (array) $user;
    }

    $dsn = "mysql:host=" . $credentials['host'];
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      return new PDO($dsn, $credentials['username'], $credentials['password'], $options);
    } catch (PDOException $e) {
      throw $e;
    }
  }

  /**
   * Get connection instance
   *
   * @return object
   */
  public static function getInstance($credentials = null): object
  {
    if (self::$instance == null) {
      self::$instance = new Database($credentials);
    }

    return self::$instance;
  }

  /**
   * Prepare statement with query
   *
   * @param string $sql
   * @return void
   */
  public function query($sql): void
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  /**
   * Bind values
   *
   * @param string $param
   * @param string $value
   * @param string $type
   * @return void
   */
  public function bind($param, $value, $type = null): void
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  /**
   * Execute the prepared statement
   *
   * @return bool
   */
  public function execute(): bool
  {
    return $this->stmt->execute();
  }

  /**
   * Get result set as array of objects
   *
   * @return bool
   */
  public function resultSet(): bool
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Get single record as object
   *
   * @return bool
   */
  public function single(): bool
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get row count
   *
   * @return int
   */
  public function rowCount(): int
  {
    return $this->stmt->rowCount();
  }
}
