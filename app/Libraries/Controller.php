<?php

namespace App\Libraries;

class Controller
{
  /**
   * Load models
   *
   * @param string $model
   * @return object
   */
  public function model($model): object
  {
    return new $model();
  }

  /**
   * Load Views
   *
   * @param string $view
   * @param array $data
   * @return void
   */
  public function view($view, $data = []): void
  {
    if (!file_exists('../resources/views/' . $view . '.php')) {
      die('View does not exist');
    }

    require_once '../resources/views/' . $view . '.php';
  }
}
