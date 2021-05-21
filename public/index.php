<?php

use App\Libraries\Core;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();
$app = new Core;
