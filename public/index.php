<?php

require_once '../core/Autoloader.php';
core\Autoloader::register(__DIR__ . '/..');

use core\Router;

$router = new core\Router();

// Définition des routes
$router->addRoute('/', 'Src\controllers\HomeController', 'index');
$router->addRoute('/users', 'Src\controllers\UserController', 'list');

// Récupération de l'URL demandée
$url = $_SERVER['REQUEST_URI'];

// Dispatch de la requête
$router->dispatch($url);
?>

