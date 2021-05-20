<?php

namespace App\Http\Controllers;

use App\Helpers\Log;
use App\Helpers\Request;
use App\Libraries\Controller;
use App\Libraries\Database;

class Api extends Controller
{
    public function results()
    {
        $db = Database::getInstance();

        $db_name = Request::input('db_name');
        $sql = Request::input('query');

        try {
            $results = $db->sql($db_name, $sql);
            header('Content-Type: application/json');
            print json_encode($results);
        } catch (\Throwable $th) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            Log::error($th->getMessage());
            die(json_encode(array('message' => $th->getMessage(), 'code' => 500)));
        }
    }
}
