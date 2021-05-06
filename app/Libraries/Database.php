<?php

namespace App\Libraries;

use App\Helpers\Cookie;
use App\Helpers\Redirect;
use App\Helpers\Session;
use App\Helpers\Storage;
use \Pdo;
use \PDOException;

/*
* PDO Database Class
* Connect to database
* Create prepared statements
* Bind values
* Return rows and results
*/

class Database
{
  public static $dbh;
  public static $stmt;
  public static $error;


  public static function init()
  {
    if (Cookie::exists('user_id')) {
      $user = Storage::getUserById(Cookie::get('user_id'));
      static::connect($user->host, $user->username, $user->password);
    }
  }

  public static function connect($host, $username, $password)
  {
    // Set DSN
    $dsn = "mysql:host=$host";
    $options = array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    // Create PDO instance
    try {
      self::$dbh = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
      self::$error = $e->getMessage();

      Session::flash('login_failed', self::$error);
      Session::flash('host', $host);
      Session::flash('username', $username);

      Redirect::To('/login');
    }
  }

  // Prepare statement with query
  public function query($sql)
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  // Bind values
  public function bind($param, $value, $type = null)
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

  // Execute the prepared statement
  public function execute()
  {
    return $this->stmt->execute();
  }

  // Get result set as array of objects
  public function resultSet()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  // Get single record as object
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  // Get row count
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
}
