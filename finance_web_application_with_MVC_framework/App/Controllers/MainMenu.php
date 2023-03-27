<?php

namespace App\Controllers;

use \Core\View;

class MainMenu extends Authenticated {

    public function indexAction() {

        View::renderTemplate('MainMenu/index.html');
    }

}