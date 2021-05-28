<?php

namespace App\Http\Controllers;

use App\Helpers\Log;
use App\Helpers\Request;
use App\Libraries\Controller;
use App\Libraries\Database;

class Api extends Controller
{
    /**
     *
     * @var Request $request
     */
    private $request;

    /**
     *
     * @var Log $logger
     */
    private $logger;

    /**
     *
     * @var Database $database
     */
    private $database;

    /**
     * Api Contructor
     *
     * @param Request $request
     * @param Log $logger
     * @param Database $database
     */
    public function __construct(Request $request, Log $logger, Database $database)
    {
        $this->request = $request;
        $this->logger = $logger;
        $this->database = $database;
    }

    public function results()
    {
        $db_name = $this->request->input('db_name');
        $sql = $this->request->input('query');

        try {
            $results = $this->database->sql($db_name, $sql);
            header('Content-Type: application/json');
            print json_encode($results);
        } catch (\Throwable $th) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            $this->logger->error($th->getMessage());
            die(json_encode(['message' => $th->getMessage(), 'code' => 500]));
        }
    }
}
