<?php

use App\Helpers\Session;
use App\Libraries\Core;
use App\Libraries\Database;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

Session::start();
Database::init();

$app = new Core;
