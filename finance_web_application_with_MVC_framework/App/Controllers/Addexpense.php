<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Flash;

class AddExpense extends Authenticated {

    public function newAction() {

        View::renderTemplate('AddExpense/new.html');
    }

    public function addAction() {

        $expense = new Expense($_POST);

        if($expense->saveRecord()) {

            Flash::addMessage('Rekord dodany');
            $this->redirect('/addexpense/new');
        }

        Flash::addMessage('Coś poszło nie tak', Flash::DANGER);
        $this->redirect('/addexpense/new');

    }


    public function limitAction() {

        //$user_id = $this->user->id;

        $category = $this->routeMatchedParams['category'];

        echo json_encode(Expense::getLimit($category), JSON_UNESCAPED_UNICODE);

    }

    public function sumAction() {

        $category = $this->routeMatchedParams['category'];
        //$category = 'transport';
        $date = $this->routeMatchedParams['date'];

        echo json_encode(Expense::getExpensesSumInSelectedCategoryAndDate($date, $category), JSON_UNESCAPED_UNICODE);
    }

    

}