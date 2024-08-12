<?php
//Chemin d'acces
define('BASE_PATH', dirname(__DIR__));

//Charger les chemin
require_once BASE_PATH . '/lib/core/Autoloader.php';
require_once BASE_PATH . '/lib/core/EnvLoader.php';
//Initialiser
lib\core\Autoloader::register();
lib\core\EnvLoader::load(BASE_PATH . '/.env');


//Connection a la BDD
$dbConnection = lib\config\Database::getConnection();

// Charger la configuration en fonction de l'environnement
//getenv() recupere les variable d'envirronement (juste changer dans .env la variable APP_ENV)
$config = require BASE_PATH . '/lib/config/' . getenv('APP_ENV') . '.php';

//Charger les routes
$routes = require BASE_PATH . '/lib/config/routes.php';

//Initialiser le Router
$router = new Lib\core\Router($routes);


// Gérer la requête
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = str_replace('/arcadia', '', $url);

try {
  $router->handleRequest($url);

} catch (Exception $e)
{
  //Gérer les erreurs
  $response = new lib\core\Responce();
  $response->setStatusCode(500);
  $response->json(['error' => 'Une erreur interne est survenue']);
  $response->send();
}
?>

