<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Libraries\Controller;

class Export extends Controller
{
    /**
     *
     * @var Redirect $redirect
     */
    private $redirect;

    /**
     *
     * @var Auth $authentication
     */
    private $authentication;

    /**
     * Api Contructor
     *
     * @param Request $request
     */
    public function __construct(Redirect $redirect, Auth $authentication)
    {
        $this->redirect = $redirect;
        $this->authentication = $authentication;
    }

    /**
     * Handles exporting databases
     *
     * @return void
     */
    public function index(Request $request): void
    {
        if (!$request->isPost()) {
            $this->redirect->to('/dashboard');
        }

        $database = $request->input('db_name');
        $user = $this->authentication->username();

        $filename = "$database.sql";
        $mime = "application/json";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = env('MYSQLDUMP_PATH') . " -u $user $database";
        passthru($cmd);
    }
}
