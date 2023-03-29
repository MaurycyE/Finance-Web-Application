<?php

namespace Core;

use \App\Authentication;
use \App\Config;
use \App\Flash;

abstract class Controller {

    protected $routeMatchedParams = [];

    public function __construct($routeMatchedParams){
        $this->routeMatchedParams = $routeMatchedParams;
    }

    public function __call($name, $args) {

        $method = $name . 'Action';

        if(method_exists($this, $method)) {
            if($this->before() !== false){
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        }
        else {
            throw new \Exception("Method $method not found in controller ". get_class($this));
        }
    }

    protected function before(){

    }

    protected function after(){
        
    }

    public function redirect($url) {

        header('Location: https://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }

    public function requireLogin() {

        if(! Authentication::getUser()) {

            Flash::addMessage('Zaloguj się, by uzyskać dostęp do tej strony', Flash::WARNING);

            Authentication::rememberRequestedPage();
            $this->redirect('/login');
        }
    }
    
}