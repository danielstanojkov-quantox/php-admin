<?php

namespace App\Libraries;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Helpers\UserStorage;
use \Pdo;
use \PDOException;
use PDOStatement;

class Database
{
  /**
   * Selected database name
   *
   * @var string
   */
  public static $database;

  /**
   * Selected table name
   *
   * @var string
   */
  
  public static $table;
  /**
   * Database Connection
   *
   * @var Pdo
   */
  public static $dbh;

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
   * Initialize database connection
   *
   * @return void
   */
  public static function init(): void
  {
    if (Cookie::exists('user_id')) {
      $user = UserStorage::getUserById(Cookie::get('user_id'));
      static::connect($user->host, $user->username, $user->password);
    }
  }

  /**
   * Establishes connection to database
   *
   * @param string $host
   * @param string $username
   * @param string $password
   * @return void
   */
  public static function connect($host, $username, $password): void
  {
    $dsn = "mysql:host=$host";
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
      self::$dbh = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
      self::$error = $e->getMessage();

      Session::flash('login_failed', self::$error);
      Session::flash('host', $host);
      Session::flash('username', $username);

      Redirect::to('/login');
    }
  }


  public static function all()
  {
    $stmt = self::$dbh->query('SHOW DATABASES;');
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public static function tables($database)
  {
    try {
      $stmt = self::$dbh->query("SHOW TABLES FROM $database;");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      session('db_not_found', "Database doesn't exist");
      Redirect::To('/dashboard');
    }
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
