<?php

namespace lib\core;

use lib\config\database;
use Exception;

class Router
{   
    
    private $routes = [];
    private $dbConnection;

    //le constructeur accepte un tableau de route optionnel et la connection a la Base
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
        $this->dbConnection = database::getConnection();
    }

    //Methode pour ajouter une nouvelle route
    public function addRoute($route, $handler)
    {
        //Ajoute une route et son gestionnaire au tableau 
        $this->routes[$route] = $handler;
    }


    //Method principal pour gérer le requete entrante
    public function handleRequest($url)
    {
        //Récupere la methode HTTP de requete actuelle
        $method = $_SERVER['REQUEST_METHOD'];
        error_log("Handling request for URL: " . $url . " with method: " . $method);

        //je parcours toutes les routes enregistrer
        foreach ($this->routes as $route)
        {   
            //error_log("Checking route: " . $route['path']);
            //Vérifie si la method HTTP correspond et si URL correspond au chemin de la route
            if ($route['method'] === $method && $this->matchRoute($route['path'], $url, $params)) {
                // Sépare le nom du contrôleur et de la méthode
                list($controller, $action) = explode('@', $route['handler']);
                // Ajoute le namespace complet au nom du contrôleur
                $controller = "App\\Controllers\\" . $controller;

                error_log("Matched route. Controller: " . $controller . ", Action: " . $action);

                if (!class_exists($controller)) {
                    error_log("Controller class does not exist: " . $controller);
                    throw new Exception("Controller not found: " . $controller);
                }

                // Crée une instance du contrôleur
                $controllerInstance = new $controller($this->dbConnection);

                if (!method_exists($controllerInstance, $action)) {
                    error_log("Action does not exist in controller: " . $action);
                    throw new Exception("Action not found: " . $action);
                }

                error_log("Calling controller action");
                // Appelle la méthode du contrôleur avec les paramètres extraits de l'URI
                $response = call_user_func_array([$controllerInstance, $action], $params ?? []);
                
                error_log("Controller action returned. Response type: " . gettype($response));
                // Gère le résultat retourné par le contrôleur
                if (!($response instanceof Response)) {
                    // Si c'est déjà un objet Response, on l'utilise tel quel
                    error_log("Controller did not return a Response object. Actual type: " . gettype($response));
                    throw new Exception("Controller did not return a Response object");
                } 
                error_log("Returning response from Router");
                return $response;
            }
        }
        error_log("No matching route found for URL: " . $url);
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