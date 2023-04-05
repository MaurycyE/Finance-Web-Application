<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;

class ViewBalance extends Authenticated {

    public function viewAction() {

        View::renderTemplate('ViewBalance/view.html');
    }

    public function balanceAction() {

        $balance = new Balance($_POST);
        $balance->getAllResults();
        $this->redirect('\viewbalance\view');
    }
}