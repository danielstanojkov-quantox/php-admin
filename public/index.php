<?php

use App\Helpers\Session;

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

\App\Helpers\Session::start();
$init = new \App\Libraries\Core;
