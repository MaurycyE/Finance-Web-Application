<?php

namespace App\Controllers;

use \Core\View;
//use \App\Authentication;

class MainMenu extends Authenticated {

    public function indexAction() {

        View::renderTemplate('MainMenu/index.html');
    }

}