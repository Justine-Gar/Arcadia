<?php

namespace App\Controllers;

use Exception;
use lib\core\Response;

class Controllers
{

  protected function render($view, $data = [])
  {
    //error_log("Render method called with view: " . $view);

    $viewPath = BASE_PATH . '/src/views';
    $viewName = ucfirst($view);
    $viewPath = $viewPath . '/pages/' . $viewName . '.php';

    //error_log("View path: " . $viewPath);

    if (!file_exists($viewPath)) {
      //error_log("View file not found: " . $viewPath);
      throw new Exception("La vue '$view' n'existe pas.");
    }

    // Extrait les données pour qu'elles soient disponibles dans la vue
    extract($data);

    // Capture le contenu de la vue
    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    //error_log("View content captured. Length: " . strlen($content));

    // Capture le layout avec le contenu de la vue
    ob_start();
    include BASE_PATH . '/src/views/layout.php';
    $fullContent = ob_get_clean();

    //error_log("Full content captured. Length: " . strlen($fullContent));

    // Crée et retourne une Response
    $response = new Response();
    $response->setContent($fullContent);

    //error_log("Response object created and returned");
    return $response;
  }

  
  protected function redirect($url)
  {
      $response = new Response();
      $response->setStatusCode(302);
      $response->setHeader('Location', $url);
      return $response;
  }

  

}
