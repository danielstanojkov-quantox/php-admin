<?php

namespace App\Libraries;

/*
* Base Controller
* Loads the models and views
*/

class Controller
{
  public function model($model)
  {
    return new $model();
  }

  // Load view
  public function view($view, $data = [])
  {
    if (!file_exists('../resources/views/' . $view . '.php')) {
      die('View does not exist');
    }

    require_once '../resources/views/' . $view . '.php';
  }
}
