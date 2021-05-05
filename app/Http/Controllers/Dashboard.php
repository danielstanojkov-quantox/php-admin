<?php

namespace App\Http\Controllers;

use App\Libraries\Controller;

class Dashboard extends Controller
{
  public function index()
  {
    $data = [];

    $this->view('dashboard/index', $data);
  }
}
