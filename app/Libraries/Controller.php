<?php

namespace App\Libraries;

class Controller
{
  /**
   * Load Views
   *
   * @param string $view
   * @param array $data
   * @return void
   */
  public function view(string $view, array $data = []): void
  {
    if (!file_exists('../resources/views/' . $view . '.php')) {
      die('View does not exist');
    }

    require_once '../resources/views/' . $view . '.php';
  }
}
