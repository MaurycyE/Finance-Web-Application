<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

class ChangeSettings extends Authenticated {

    public function incomeAction() {

        View::renderTemplate('Settings/incomeSetting.html');
    }

    public function expenseAction() {

        View::renderTemplate('Settings/expenseSetting.html');
    }

    public function userAction() {

        View::renderTemplate('Settings/userSetting.html');
    }

    public function updateAction() {

        $user = new User($_POST);
        
        if($user->update()) {
            
            Flash::addMessage('Dane zmienione!', FLASH::SUCCESS);
            $this->redirect('\changesettings\user');
        }

        echo "Fuckup";
        exit;
    }
}