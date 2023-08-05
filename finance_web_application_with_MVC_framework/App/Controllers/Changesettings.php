<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Models\Settings;
use \Core\Error;

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
            $this->redirect('/changesettings/user');
        }

        else {

            Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            $this->redirect('/changesettings/user');
        }

    }

    public function changePasswordAction() {

        $user = new User($_POST);

        if($user->changePassword()) {
            
            Flash::addMessage('Dane zmienione!', Flash::SUCCESS);
            $this->redirect('/changesettings/user');
        }

        else {

            $this->redirect('/changesettings/user');
        }
    }

    protected function redirectToCurrentTab($settings) {

        if($settings->categoryType=='incomeCategory')
            $this->redirect('/changesettings/income');
        else
            $this->redirect('/changesettings/expense');
    }

    public function addCategoryAction() {

        $settings = new Settings($_POST);

        if($settings->checkCategoryName()) {

            Flash::addMessage('Dodano nową kategorię!', Flash::SUCCESS);
            $this->redirectToCurrentTab($settings);
        }

        else {

            //Flash::addMessage('Wystąpił błąd!', Flash::WARNING);
            $this->redirectToCurrentTab($settings);
        }

    }

    public function deleteCategoryAction() {

        $settings = new Settings($_POST);

        $settings->idCategoryToDelete = $settings->findIdCategory();
        $settings->deleteRelatedRecords();

            if($settings->deleteCategory()) {

                Flash::addMessage('Usunięto kategorię!', Flash::SUCCESS);
                $this->redirectToCurrentTab($settings);
            }

        else {

        Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
        $this->redirectToCurrentTab($settings);
        }
    }

    public function changeCategoryNameAction() {

        $settings = new Settings($_POST);

        if(!$settings->findCategoryByName()) {

            if($settings->renameCategory()) {

                Flash::addMessage('Nazwa zmieniona!', Flash::SUCCESS);
                $this->redirectToCurrentTab($settings);
            }

            else {

                Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
                $this->redirectToCurrentTab($settings);
            }
            
        }

        else {

            Flash::addMessage("Podana nazwa już istnieje!", Flash::WARNING);
            $this->redirectToCurrentTab($settings);
        }
    }

    public function deleteAccountAction() {

        $settings = new Settings($_POST);

        if($settings->deleteAccount()) {

            session_unset();
            $this->redirect('/');
        }

        else {

            Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            $this->redirect('/changesettings/user');
        }

    }

    public function updateIncomeRecordAction() {

        $settings = new Settings($_POST);

        $settings->updateIncomeRecord();
        $this->redirect('/viewbalance/balance');
    }

    public function updateExpenseRecordAction() {

        $settings = new Settings($_POST);

        $settings->updateExpenseRecord();
        $this->redirect('/viewbalance/balance');
    }

    public function updateLimitAction() {

        $settings = new Settings($_POST);

        // var_dump($settings);
        // exit;
        // echo "działa";

        // exit;
        $settings->updateExpenseLimit();

       $this->redirectToCurrentTab($settings);

    }

}