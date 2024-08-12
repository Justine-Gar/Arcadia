<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', 'D:/ENV/wamp64/www/Arcadia/php_errors.log');
error_log("Script started");

//Chemin d'acces
define('BASE_PATH', dirname(__DIR__));


//Charger l'autoloader
require_once BASE_PATH . '/lib/core/Autoloader.php';
//Initialiser
\lib\core\Autoloader::register();

//Charger envirronement
require_once BASE_PATH . '/lib/core/EnvLoader.php';

\lib\core\EnvLoader::load(BASE_PATH . '/.env');

$appEnv = getenv('APP_ENV');
if (!$appEnv) {
    die("La variable d'environnement APP_ENV n'est pas définie.");
}

$configFile = BASE_PATH . '/lib/config/' . $appEnv . '.php';
if (!file_exists($configFile)) {
    die("Le fichier de configuration n'existe pas : " . $configFile);
}
$config = require $configFile;

//Charger le gestion d'erreur 
require_once BASE_PATH . '/lib/core/Logger.php';
\lib\core\Logger::init('/lib/logs');

//Connection a la BDD
$dbConnection = \lib\config\Database::getConnection();

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

  lib\core\Logger::info('Traitement de la requête : ' . $uri);
  $response= $router->handleRequest($url);
  
  if (!($response instanceof \lib\core\Response)) {
    throw new Exception("Router did not return a Response object");
  }

  $response->send();

} catch (Exception $e)
{
  //Gérer les erreurs
  lib\core\Logger::error('Erreur lors du traitement de la requête : ' . $e->getMessage());
  // Ajouter un log pour voir l'URL traitée
  $response = new \lib\core\Response();
  $response->setStatusCode(500);
  $response->json(['error' => 'Une erreur interne est survenue: ' . $e->getMessage()]);
  $response->send();
}


?>

