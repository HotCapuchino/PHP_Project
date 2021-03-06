<?php

namespace app\core;

class Router {

    protected $routes = [];
    protected $found_route = null;
    protected $current_route = '/';

    public function __construct() {
        $this->routes = require('app/config.php');
    }  

    public function matchRoute() {
        $current_url = $_SERVER['REQUEST_URI'];
        foreach ($this->routes as $route => $key) {
            $pattern = '#^' . $route . '$#';
            if (preg_match($pattern, $current_url, $matches)) {
                $this->found_route = $route;
                return true;
            }
        }
        return false;
    }

    public function start() {
        if ($this->matchRoute()) {
            $path = 'app\controllers\\' . ucfirst($this->routes[$this->found_route]['controller']) . 'Controller';
            if (class_exists($path)) {
                $controller_function = $this->routes[$this->found_route]['action'] . 'Action';
                $current_controller = new $path($this->routes[$this->found_route]['controller'], 
                                                $this->routes[$this->found_route]['action'], 
                                                $this->routes[$this->found_route]['view']);
                $route_available = $this->routes[$this->found_route]['open'];
                // if (!$route_available) {
                //     header('Location: ' . strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]) . '://' . $_SERVER['HTTP_HOST'] . $this->current_route);
                // }
                if (method_exists($path, $controller_function)) {
                    $this->current_route = $this->found_route;
                    $current_controller->$controller_function();
                }
            }
        } else {
            header('Location: ' . strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]) . '://' . $_SERVER['HTTP_HOST'] . $this->current_route);
        }
    }
}