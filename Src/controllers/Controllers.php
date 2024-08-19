<?php

namespace App\Controllers;

use lib\core\Response;

class Controllers
{

  protected function render($view, $data = [], $domain = 'pages')
  {
    //error_log("Render method called with view: " . $view);

    $viewPath = BASE_PATH . '/src/views';
    $viewName = ucfirst($view);
    $viewPath = $viewPath . '/' . $domain . '/' . $viewName . '.php';

    // Extrait les données pour qu'elles soient disponibles dans la vue
    extract($data);

    // Capture le contenu de la vue
    ob_start();
    include $viewPath;
    $content = ob_get_clean();


    // Capture le layout avec le contenu de la vue
    ob_start();
    include BASE_PATH . '/src/views/layout.php';
    $fullContent = ob_get_clean();


     // Crée et retourne une Response
    $response = new Response();
    $response->setContent($fullContent);

    return $response;
  }

}
