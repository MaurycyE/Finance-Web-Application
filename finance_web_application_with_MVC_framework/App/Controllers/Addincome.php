<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\Income;

class AddIncome extends Authenticated {

    public function newAction() {

        View::renderTemplate('AddIncome/new.html');
    }

    public function addAction() {

        $income = new Income($_POST);

        if($income->saveRecord()) {

            Flash::addMessage('Rekord dodany!' );
            $this->redirect('/addincome/new');
        }

        Flash::addMessage('Coś poszło nie tak!', Flash::DANGER);
        $this->redirect('/addincome/new');
    }
}