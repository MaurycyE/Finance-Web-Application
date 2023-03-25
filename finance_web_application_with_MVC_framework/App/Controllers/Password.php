<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Password extends \Core\Controller  {

    public function forgotAction() {

        View::renderTemplate('PasswordRestore/forgotenPasswordRequest.html');
    }

    public function requestResetAction() {

        User::sendPasswordReset($_POST['email']);

        View::renderTemplate('PasswordRestore/reset_requested.html');
    }

    public function resetAction() {

        $token = $this->routeMatchedParams['token'];

        $user = $this->getUserOrExit($token);

        View::renderTemplate('PasswordRestore/reset_password_site.html', [

            'token' => $token
        ]);
    }

    public function resetPasswordAction() {

        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if($user->resetPassword($_POST['password'])) {

            View::renderTemplate('PasswordRestore/reset_success_message.html');
        }

        else {

            View::renderTemplate('PasswordRestore/reset_password_site.html', [

                'token' => $token,
                'user' => $user
            ]);
        }
    }

    protected function getUserOrExit($token) {

        $user = User::findByPasswordReset($token);

        if($user) {

            return $user;
        }

        else {

            View::renderTemplate('PasswordRestore/token_expired_message.html');
            exit;
        }

    }

}