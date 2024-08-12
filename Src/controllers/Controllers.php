<?php
namespace App\Controllers;

use Exception;

class Controllers {

  protected function render($view, $data = [])
  {
    $viewPath = BASE_PATH . '/src/views';
    $viewName = ucfirst($view);
    $viewPath = $viewPath . '/pages/' . $viewName . '.php';

    if (!file_exists($viewPath))
    {
      throw new Exception("La vue '$view' n'existe pas.");
    }

    //Extrait les données pour qu'elles soient disponible dans la vue
    extract($data);
    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    //charge le layout principal
    include BASE_PATH . '/src/views/layout.php';
  }
}