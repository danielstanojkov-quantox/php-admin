<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Libraries\Controller;

class Export extends Controller
{
    /**
     * Handles exporting databases
     *
     * @return void
     */
    public function index(): void
    {
        if (!Request::isPost()) {
            Redirect::to('/dashboard');
        }

        $database = Request::input('db_name');
        $user = Auth::username();

        $filename = "$database.sql";
        $mime = "application/json";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = env('MYSQLDUMP_PATH') . " -u $user $database";
        passthru($cmd);
    }
}
