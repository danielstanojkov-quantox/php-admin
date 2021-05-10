<?php

namespace App\Http\Controllers;

use App\Helpers\Redirect;
use App\Http\Middleware\IsAuthenticatedMiddleware;
use App\Libraries\Controller;
use App\Libraries\Database;

class Dashboard extends Controller
{
  /**
   * Displays dashboard view to user
   *
   * @return void
   */
  public function index(): void
  {
    if (!IsAuthenticatedMiddleware::handle()) {
      Redirect::to('/login');
    }
    
    $this->view('dashboard/index', $data = []);
  }
}
