<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Authentication;

class Login extends \Core\Controller {

    public function newAction() {

        View::renderTemplate('Login/new.html');
    }

    public function createAction() {

        $user = User::authenticate($_POST['email'], $_POST['password']);

        if($user) {

            Authentication::login($user);
            $this->redirect(Authentication::getReturnToPage());
        }
        else {
            View::renderTemplate('Login/new.html', [

                'email' => $_POST['email'],

            ]);
        }
    }

    public function destroyAction() {

        Authentication::logout();
        $this->redirect('/finance_web_application_with_MVC_framework/public/');
    }
    
}