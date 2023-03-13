<?php

namespace App;

use \App\Models\User;

class  Authentication {

    public static function login($user) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id_users;
    }

    public static function logout() {

        $_SESSION = [];

        if(ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
      
    }

    public static function rememberRequestedPage() {

        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    public static function getReturnToPage() {

        return $_SESSION['return_to'] ?? '/finance_web_application_with_MVC_framework/public/';
    }

    public static function getUser() {

        if(isset($_SESSION['user_id'])) {

            return User::findByID($_SESSION['user_id']);
        }
    }

}