<?php

namespace lib\core;

use App\Utils\UserService;
use lib\core\Response;
use Exception;

class Router
{   
    
    private $routes = [];


    //le constructeur accepte un tableau de route optionnel et la connection a la Base
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }


    //Method principal pour gérer le requete entrante
    public function handleRequest($url)
    {
        //Récupere la methode HTTP de requete actuelle
        $method = $_SERVER['REQUEST_METHOD'];

        $user = false;
        //je parcours toutes les routes enregistrer
        foreach ($this->routes as $route)
        {   
            //Vérifie si la method HTTP correspond et si URL correspond au chemin de la route
            if ($route['method'] === $method && $this->matchRoute($route['path'], $url, $params)) {

                //si jamais clé Role existe vérifié si la personn ete connecté et possede ce role
                //si route clé role
                if (isset($route['roles']) && !empty($route['roles'])) {

                    //vérifier role 
                    if ($user === false) {

                        $userService = new UserService; //getInstanceUser
                        $user = $userService->getCurrentUser(); 
                    }
                    if (!$user) {
                        //axes d'amélioration
                        echo 'user non connecter';
                        exit;
                    }

                    elseif(!in_array($user->getRole(), $route['roles'])) {

                        echo 'user connecter mauvais role';
                        exit;
                    }
                }

                // Sépare le nom du contrôleur et de la méthode
                list($controller, $action) = explode('@', $route['handler']);
                // Ajoute le namespace complet au nom du contrôleur
                $controller = "App\\Controllers\\" . $controller;

                if (!class_exists($controller)) {

                    throw new Exception("Controller pas trouvé: " . $controller);
                }

                // Crée une instance du contrôleur
                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $action)) {
                    throw new Exception("Action not found: " . $action);
                }

                // Appelle la méthode du contrôleur avec les paramètres extraits de l'URI
                $response = call_user_func_array([$controllerInstance, $action], $params ?? []);
                
                // Gère le résultat retourné par le contrôleur
                if (!($response instanceof Response)) {
                    // Si c'est déjà un objet Response, on l'utilise tel quel
                    //Logger::error("Controller returned: " . gettype($response));
                    throw new Exception("Controller ne return pas objet Response");
                } 
                return $response;
            }
        }
        //error_log("No matching route found for URL: " . $url);
        // Si aucune route ne correspond, retournez une réponse 404
        $response = new Response();
        $response->setStatusCode(404);
        $response->setContent("404 Not Found");
        return $response;
    }

    //Method pour faire correspondre les route à url
    private function matchRoute($routePath, $url, &$params)
    {
        //Convertir les params de la route en expression réguliere
        $routePath = preg_replace('/{(\w+)}/', '(?P<\1>[^/]+)', $routePath);
        //Ajouter les délimiteur début et fin à l'expresion reguliere
        $routePath = "#^" . $routePath . "$#";

        //On fait correspondre L url à la route
        if (preg_match($routePath, $url, $matches))
        {
            //Extrait les param de l'url
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }
}