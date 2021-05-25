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
     *
     * @var Request $request
     */
    private $request;

    /**
     *
     * @var Redirect $redirect
     */
    private $redirect;

    /**
     *
     * @var Log $logger
     */
    private $logger;

    /**
     *
     * @var Session $session
     */
    private $session;

    /**
     *
     * @var File $file
     */
    private $file;

    /**
     *
     * @var Database $database
     */
    private $database;

    /**
     *
     * @var ImportFileRequest $importRequest
     */
    private $importRequest;

    /**
     * Import Constructor
     *
     * @param Request $request
     * @param Redirect $redirect
     * @param File $file
     * @param Log $logger
     * @param Session $session
     * @param Database $database
     * @param ImportFileRequest $importRequest
     */
    public function __construct(
        Request $request,
        Redirect $redirect,
        File $file,
        Log $logger,
        Session $session,
        Database $database,
        ImportFileRequest $importRequest
    ) {
        $this->request = $request;
        $this->redirect = $redirect;
        $this->file = $file;
        $this->logger = $logger;
        $this->session = $session;
        $this->database = $database;
        $this->importRequest = $importRequest;
    }

    /**
     * Handles importing databases with sql files
     *
     * @return void
     */
    public function index(): void
    {
        if (!$this->request->isPost()) {
            $this->redirect->to('/dashboard');
        }

        $dbName = $this->request->input('db_name');
        $file = $this->request->file('sql_file');

        $uri = $dbName ? "/dashboard?db_name=$dbName" : '/dashboard';

        if (!$this->importRequest->validate($dbName, $file)) {
            $this->redirect->to($uri);
        }

        $sql = $this->file->get($file['tmp_name']);

        try {
            $this->database->import($dbName, $sql);
            $this->logger->info("Database $dbName has been imported successfully");
            $this->session->flash('import__success', "Your database has been imported successfully.");
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            $this->session->flash('import__error', $th->getMessage());
        }

        $this->redirect->to($uri);
    }
}
