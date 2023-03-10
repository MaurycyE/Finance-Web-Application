<?php

namespace Core;

Class Router {

    protected $routes = [];
    protected $parametersFromMatchedRoute = [];

    public function add($route, $parameters = []) {

        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P</1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $parameters;
    }

    public function getRoutes() {

        return $this->routes;
    }

    public function match($url) {

        foreach($this->routes as $route => $params) {

            if(preg_match($route, $url, $matches)){

                foreach($matches as $key => $match){
                    if(is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->parametersFromMatchedRoute = $params;
                return true;
            }
        }
        return false;
    }

    public function getParams(){

        return $this->parametersFromMatchedRoute;
    }

    public function dispatch($url) {

        $url = $this->removeQueryStringVariables($url);

        if($this->match($url)) {

            $controller = $this->parametersFromMatchedRoute['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if(class_exists($controller)){
                $controller_object = new $controller($this->parametersFromMatchedRoute);

                $action = $this->parametersFromMatchedRoute['action'];
                $action = $this->convertToCamelCase($action);

                if(preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();
                }

                else {
                    throw new \Exception("Method $action in $controller cannot be called directly - remove the Action suffix to call this method");
                }
            }
            else {
                throw new \Exception("Controller class $controller not found");
            }
        }
        else {
            throw new \Exception('No route matched.', 404);
        } 
    }


    protected function convertToStudlyCaps($string) {

        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string){

        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url){

        if($url != '') {

            $parts = explode('&', $url, 2);

            if(strpos($parts[0], '=') === false) {
                $url = $parts[0];
            }
            else {
                $url = '';
            }
        }

        return $url;
    }

    protected function getNamespace(){

        $namespace = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->parametersFromMatchedRoute)){
            $namespace.= $this->parametersFromMatchedRoute['namespace'] . '\\';
        }

        return $namespace;
    }
    
}