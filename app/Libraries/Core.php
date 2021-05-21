<?php

namespace App\Libraries;

use DI\ContainerBuilder;

/*
* Creates URL & loads core controller
* URL FORMAT - /controller/method/params
*/

class Core
{
  /**
   * Current Controller
   *
   * @var mixed
   */
  protected $currentController = 'Dashboard';

  /**
   * Method on the current controller
   *
   * @var string
   */
  protected $currentMethod = 'index';

  /**
   * URL parameters
   *
   * @var array
   */
  protected $params = [];

  /**
   * Core class constructor method
   */
  public function __construct()
  {
    $url = $this->getUrl();

    if (isset($url[0]) && file_exists('../app/Http/Controllers/' . ucwords($url[0]) . '.php')) {
      $this->currentController = ucwords($url[0]);
      unset($url[0]);
    }

    $builder = new ContainerBuilder();
    $container = $builder->build();

    $class = "App\Http\Controllers\\" . $this->currentController;
    $this->currentController = $container->get($class);

    if (isset($url[1])) {
      if (method_exists($this->currentController, $url[1])) {
        $this->currentMethod = $url[1];
        unset($url[1]);
      }
    }

    $this->params = $url ? array_values($url) : [];

    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  /**
   * Filter the current url
   *
   * @return mixed
   */
  public function getUrl(): mixed
  {
    if (!isset($_GET['url'])) {
      return null;
    }

    $url = rtrim($_GET['url'], '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    return $url;
  }
}
