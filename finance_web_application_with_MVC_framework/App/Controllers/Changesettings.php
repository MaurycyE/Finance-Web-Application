<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Models\Settings;

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
            
            Flash::addMessage('Dane zmienione!', Flash::SUCCESS);
            $this->redirect('\changesettings\user');
        }

        else {

            Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            $this->redirect('\changesettings\user');
        }

        // echo "Fuckup";
        // exit;
    }

    public function changePasswordAction() {

        $user = new User($_POST);

        if($user->changePassword()) {
            
            Flash::addMessage('Dane zmienione!', Flash::SUCCESS);
            $this->redirect('\changesettings\user');
        }

        else {

            //Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            $this->redirect('\changesettings\user');
        }
    }

    public function addCategoryAction() {

        $settings = new Settings($_POST);

        if($settings->checkCategoryName()) {

            Flash::addMessage('Dodano nową kategorię!', Flash::SUCCESS);
            $this->redirect('\changesettings\income');
        }

        else {

            Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            $this->redirect('\changesettings\income');
        }

    }

    public function deleteCategoryAction() {

        $settings = new Settings($_POST);

        try {

        $settings->idCategoryToDelete = $settings->findIdCategory();
        $settings->deleteRelatedRecords();

            if($settings->deleteCategory()) {

                Flash::addMessage('Usunięto kategorię!', Flash::SUCCESS);
                $this->redirect('\changesettings\income');
            }

            if(!$settings->deleteCategory())
                throw new Exception('Wystąpił błąd!');
        }
        catch (Exception $e) {

            Flash::addMessage($e->getMessage(), Flash::DANGER);
            $this->redirect('\changesettings\income');
        }

    }

    public function changeCategoryNameAction() {

        $settings = new Settings($_POST);

        try {

            if($settings->renameCategory()) {

                Flash::addMessage('Nazwa zmieniona!', Flash::SUCCESS);
                $this->redirect('\changesettings\income');
            }

            if(!$settings->renameCategory())
                throw new Exception('Wystąpił błąd!');
        }

        catch (Exception $e) {

            Flash::addMessage($e->getMessage(), Flash::DANGER);
            $this->redirect('\changesettings\income');
        }
    }




}