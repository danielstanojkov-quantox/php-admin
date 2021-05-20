<?php

namespace App\Http\Requests;

use App\Helpers\Session;

class ImportFileRequest
{
    /**
     * Validate the uploaded file
     *
     * @param mixed $dbName
     * @param array $file
     * @return bool 
     */
    public static function validate(mixed $dbName, array $file): bool
    {
        if (empty(trim($dbName)) || is_null($dbName)) {
            Session::flash('import__error', 'Please select database first.');
            return false;
        }

        if (empty(trim($file['name']))) {
            Session::flash('import__error', 'No file uploaded.');
            return false;
        }

        if ($file['type'] !== 'application/sql') {
            Session::flash('import__error', 'Invalid file type! Must be a .sql file.');
            return false;
        }

        return true;
    }
}
