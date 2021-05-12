<?php

use App\Helpers\Session;
use App\Libraries\Core;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

Session::start();
$app = new Core;
