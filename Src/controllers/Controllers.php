<?php

namespace App\Controllers;

use lib\core\Response;

class Controllers
{
  public function __construct()
  {
    
  }

  protected function render($view, $data = [], $domain = 'pages')
  {
    //error_log("Render method called with view: " . $view);

    $viewPath = BASE_PATH . '/src/views';
    $viewName = ucfirst($view);
    $viewPath = $viewPath . '/' . $domain . '/' . $viewName . '.php';

    // Débogage : vérifiez si le fichier de vue existe
    if (!file_exists($viewPath)) {
      throw new \Exception("Vue non trouvée: $viewPath");
    }

    // Extrait les données pour qu'elles soient disponibles dans la vue
    extract($data);

    // Capture le contenu de la vue
    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    // Débogage : vérifiez le contenu capturé
    if (empty($content)) {
      error_log("Le contenu de la vue est vide.");
    }

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
