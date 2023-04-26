<?php

namespace App\Models;

use PDO;
use \Core\Models\User;

class Settings extends \Core\Model {

    public $errors = [];
    
    public function __construct($data = []) {

        foreach($data as $key => $value) {

            if($key == 'email'){
                $key = 'user_email';
            }

            $this->$key = $value;

        }
    }

    
}