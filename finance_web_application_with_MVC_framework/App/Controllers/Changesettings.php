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

        // echo "Fuckup";
        // exit;
    }

    public function changePasswordAction() {

        $user = new User($_POST);

        if($user->changePassword()) {
            
            Flash::addMessage('Dane zmienione!', Flash::SUCCESS);
            $this->redirect('/changesettings/user');
        }

        else {

            //Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
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

        // var_dump($settings);
        // exit;

        if($settings->checkCategoryName()) {

            Flash::addMessage('Dodano nową kategorię!', Flash::SUCCESS);
            // if($settings->categoryType=='incomeCategory')
            //     $this->redirect('/changesettings/income');
            // else
            //     $this->redirect('/changesettings/expense');
            $this->redirectToCurrentTab($settings);
        }

        else {

            Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
            // if($settings->categoryType=='incomeCategory')
            //     $this->redirect('/changesettings/income');
            // else
            //     $this->redirect('/changesettings/expense');
            $this->redirectToCurrentTab($settings);
        }

    }

    public function deleteCategoryAction() {

        $settings = new Settings($_POST);

        $settings->idCategoryToDelete = $settings->findIdCategory();
        $settings->deleteRelatedRecords();

            if($settings->deleteCategory()) {

                Flash::addMessage('Usunięto kategorię!', Flash::SUCCESS);
                // $this->redirect('/changesettings/income');
                $this->redirectToCurrentTab($settings);
            }

        else {

        Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
        // $this->redirect('/changesettings/income');
        $this->redirectToCurrentTab($settings);
        }
    }

    public function changeCategoryNameAction() {

        $settings = new Settings($_POST);

        if(!$settings->findCategoryByName()) {


            if($settings->renameCategory()) {

                Flash::addMessage('Nazwa zmieniona!', Flash::SUCCESS);
                // $this->redirect('/changesettings/income');
                $this->redirectToCurrentTab($settings);
            }

            else {

                Flash::addMessage('Wystąpił błąd!', Flash::DANGER);
                // $this->redirect('/changesettings/income');
                $this->redirectToCurrentTab($settings);
            }
            
        }

        else {

            Flash::addMessage("Podana nazwa już istnieje!", Flash::WARNING);
            // $this->redirect('/changesettings/income');
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

}