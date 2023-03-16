<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Authentication;
use \App\Config;
use \App\Flash;

class Login extends \Core\Controller {

    public function newAction() {

        View::renderTemplate('Login/new.html');
    }

    public function createAction() {

        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if($user) {

            Authentication::login($user, $remember_me);
            //$this->redirect(Authentication::getReturnToPage());
            Flash::addMessage('Logowanie udane');
            $this->redirect(Config::PATH_TO_MAIN_FOLDER.'?mainmenu/index');
            
        }
        else {

            Flash::addMessage('Logowanie nieudane, spróbuj jeszcze raz', Flash::DANGER);

            View::renderTemplate('Login/new.html', [

                'email' => $_POST['email'],
                'remember_me' => $remember_me

            ]);
        }
    }

    public function destroyAction() {

        Authentication::logout();
        $this->redirect(Config::PATH_TO_MAIN_FOLDER.'?login/show-logout-message');
    }

    public function showLogoutMessageAction() {

        Flash::addMessage('Logout successful');
        $this->redirect(Config::PATH_TO_MAIN_FOLDER);
    }
    
}