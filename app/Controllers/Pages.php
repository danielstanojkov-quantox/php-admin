<?php

namespace App\Controllers;

use App\Libraries\Controller;

class Pages extends Controller
{
  public function index()
  {
    $data = [
      'title' => 'TraversyMVC',
    ];

    $this->view('pages/index', $data);
  }

  public function about()
  {
    $data = [
      'title' => 'About Us',
    ];

    $this->view('pages/about', $data);
  }
}
