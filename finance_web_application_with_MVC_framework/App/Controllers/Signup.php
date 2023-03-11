<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signup extends \Core\Controller {

    public function newAction() {

        View::renderTemplate('Signup/new.html');
    }

    public function createAction() {

        $user = new User($_POST);

        if($user->save()) {

            $user->writeDefaultCategoriesToNewUser('income_category', 'incomes_deafult_categories', 'income_categories');
            $user->writeDefaultCategoriesToNewUser('expense_category', 'expense_deafult_categories', 'expense_categories');
            $user->writeDefaultCategoriesToNewUser('expense_deafult_payment_method', 'expense_payment_deafult', 'expense_payment');

            header('Location: http://' . $_SERVER['HTTP_HOST'] . 
            '/finance_web_application_with_MVC_framework/public/?signup/success', true, 303);
            exit;
        }

        else {
            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
        }
    }

    public function successAction() {

        View::renderTemplate('Signup/success.html');
    }

}