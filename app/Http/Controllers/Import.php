<?php

namespace App\Http\Controllers;

use App\Helpers\File;
use App\Helpers\Log;
use App\Helpers\Redirect;
use App\Helpers\Request;
use App\Helpers\Session;
use App\Http\Requests\ImportFileRequest;
use App\Libraries\Controller;
use App\Libraries\Database;

class Import extends Controller
{
    /**
     * Handles importing databases with sql files
     *
     * @return void
     */
    public function index(): void
    {
        if (!Request::isPost()) {
            Redirect::to('/dashboard');
        }

        $dbName = Request::input('db_name');
        $file = Request::file('sql_file');

        $uri = $dbName ? "/dashboard?db_name=$dbName" : '/dashboard';

        if (!ImportFileRequest::validate($dbName, $file)) {
            Redirect::to($uri);
        }

        $sql = File::get($file['tmp_name']);
        $db = Database::getInstance();

        try {
            $db->import($dbName, $sql);
            Session::flash('import__success', "Your database has been imported successfully.");
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Session::flash('import__error', $th->getMessage());
        }

        Redirect::to($uri);
    }
}
