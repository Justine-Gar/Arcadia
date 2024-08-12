<?php

namespace lib\core;

class Router
{
    private $routes = [];

    public function addRoute($url, $controller, $action)
    {
        $this->routes[$url] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($url)
    {
        if (array_key_exists($url, $this->routes)) {
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];

            $controllerInstance = new $controller();
            $controllerInstance->$action();
        } else {
            // Gestion des erreurs 404
            echo "Page non trouvée";
        }
    }
}