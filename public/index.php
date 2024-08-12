<?php
//Chemin d'acces
define('BASE_PATH', dirname(__DIR__));


//Charger l'autoloader
require_once BASE_PATH . '/lib/core/Autoloader.php';
//Initialiser
\lib\core\Autoloader::register();


//Charger envirronement
require_once BASE_PATH . '/lib/core/EnvLoader.php';
\lib\core\EnvLoader::load(BASE_PATH . '/.env');


// Configurer la gestion des erreurs et exceptions
set_error_handler(function($errno, $errstr, $errfile, $errline) {
  \lib\core\Logger::error("Erreur PHP : [$errno] $errstr dans le fichier $errfile à la ligne $errline");
});

set_exception_handler(function($exception) {
  \lib\core\Logger::error("Exception non capturée : " . $exception->getMessage() . "\n" . $exception->getTraceAsString());
});

register_shutdown_function(function() {
  $error = error_get_last();
  if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
      \lib\core\Logger::error("Erreur fatale : " . $error['message'] . " dans " . $error['file'] . " à la ligne " . $error['line']);
  }
});


//Charger le gestion d'erreur 
require_once BASE_PATH . '/lib/core/Logger.php';
\lib\core\Logger::init($config['log_path'] ?? BASE_PATH . '/lib/logs');


//Connection a la BDD
\lib\core\Logger::info('Tentative de connexion à la base de données');
$dbConnection = \lib\config\Database::getConnection();
\lib\core\Logger::info('Connexion à la base de données réussie');

// Charger la configuration en fonction de l'environnement
//getenv() recupere les variable d'envirronement (juste changer dans .env la variable APP_ENV)


//Charger les routes
$routes = require BASE_PATH . '/lib/config/routes.php';

//Initialiser le Router
$router = new \lib\core\Router($routes);

// Gérer la requête
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = str_replace('/arcadia', '', $url);

try {

  \lib\core\Logger::info('Début du traitement de la requête : ' . $url);
  $response = $router->handleRequest($url);
  \lib\core\Logger::info('Fin du traitement de la requête : ' . $url);
  $response->send();

} catch (Exception $e)
{
  //Gérer les erreurs
  // Ajouter un log pour voir l'URL traitée
  \lib\core\Logger::error('Erreur lors du traitement de la requête : ' . $e->getMessage() . "\n" . $e->getTraceAsString());
  $response = new \lib\core\Response();
  $response->setStatusCode(500);
  $response->json(['error' => 'Une erreur interne est survenue: ' . $e->getMessage()]);
  $response->send();
}


?>

