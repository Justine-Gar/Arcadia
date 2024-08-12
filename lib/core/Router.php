<?php

namespace lib\core;

use lib\config\database;

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
        //Je crée un objet Response pour générer la réponse HTTP
        $response = new Responce();

        //je parcours toutes les routes enregistrer
        foreach ($this->routes as $route)
        {
            //Vérifie si la method HTTP correspond et si URL correspond au chemin de la route
            if ($route['method'] === $method && $this->matchRoute($route['path'], $url, $params)) {
                // Sépare le nom du contrôleur et de la méthode
                list($controller, $action) = explode('@', $route['handler']);
                // Ajoute le namespace complet au nom du contrôleur
                $controller = "App\\Controllers\\" . $controller;

                // Crée une instance du contrôleur
                $controllerInstance = new $controller($GLOBALS['dbConnection']);
                // Appelle la méthode du contrôleur avec les paramètres extraits de l'URI
                $result = call_user_func_array([$controllerInstance, $action], $params);

                // Gère le résultat retourné par le contrôleur
                if ($result instanceof Responce) {
                    // Si c'est déjà un objet Response, on l'utilise tel quel
                    $response = $result;
                } else {
                    // Sinon, on définit le contenu de la réponse avec le résultat
                    $response->setContent($result);
                }

                // Envoie la réponse au client
                $response->send();
                return;
            }
        }
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