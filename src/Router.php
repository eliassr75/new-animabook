<?php

namespace App;

class Router
{
    private $routes = [];

    public function addRoute($method, $url, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'url' => $url,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function handleRequest()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') . '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && $route['url'] == $uri) {
                $controllerName = $route['controller'];
                $controller = new $controllerName();
                call_user_func([$controller, $route['action']]);
                return;
            }
        }

        header("HTTP/1.1 404 Not Found");
        echo '404 - Not Found';
    }
}
