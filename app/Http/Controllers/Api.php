<?php

namespace App\Http\Controllers;

use App\Libraries\Controller;
use App\Libraries\Database;

class Api extends Controller
{
    /**
     * Table contents
     *
     */
    public function table($database, $tableName, $sql = null)
    {
       
        if($sql){
            $sql = str_replace('_', ' ', $sql);
        }

        $db = Database::getInstance();
        echo json_encode(
            $db->fetchTableContents($database, $tableName, $sql)
        );
    }
}
