<?php

namespace App\Http\Requests;

use App\Helpers\Session;

class ImportFileRequest
{
    /**
     *
     * @var Session $session
     */
    public $session;

    /**
     * CreateUserRequest Constructor
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Validate the uploaded file
     *
     * @param mixed $dbName
     * @param array $file
     * @return bool 
     */
    public function validate(mixed $dbName, array $file): bool
    {
        if (empty(trim($dbName)) || is_null($dbName)) {
            $this->session->flash('import__error', 'Please select database first.');
            return false;
        }

        if (empty(trim($file['name']))) {
            $this->session->flash('import__error', 'No file uploaded.');
            return false;
        }

        if ($file['type'] !== 'application/sql') {
            $this->session->flash('import__error', 'Invalid file type! Must be a .sql file.');
            return false;
        }

        return true;
    }
}
