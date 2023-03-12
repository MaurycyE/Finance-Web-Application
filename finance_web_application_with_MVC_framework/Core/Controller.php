<?php

namespace Core;

use \App\Authentication;

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

        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }

    public function requireLogin() {

        if(! Authentication::getUser()) {

            Authentication::rememberRequestedPage();
            $this->redirect('/finance_web_application_with_MVC_framework/public/?login');
        }
    }
    
}