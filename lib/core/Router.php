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
            //je vérifie si method HTTP correspond à URL (chemin de la route)
            if ($route['method'] === $method && $this->matchRoute($route['path'], $url, $params))
            {
                
            }
        }
    }

}