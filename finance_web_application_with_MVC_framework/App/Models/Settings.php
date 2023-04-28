<?php

namespace App\Models;

use PDO;
use \Core\Models\User;
use \App\Flash;

class Settings extends \Core\Model {

    
    public function __construct($data = []) {

        foreach($data as $key => $value) {

            $this->$key = $value;

        }
    }

    static public function getIncomeCategories() {

        if(isset($_SESSION['user_id'])) {
        $sql = "SELECT income_category FROM income_categories WHERE id_users = :idLoggedUser";

        $db=static::getDB();
        $stmt = $db->prepare($sql);

        $stmt -> bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
        }
    }

    protected function findCategoryByName() {

        $sql = "SELECT * FROM income_categories WHERE id_users = :idLoggedUser AND income_category=:newUserCategory";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue('newUserCategory', lcfirst($this->newCategoryName), PDO::PARAM_STR);

        return $stmt->execute();

    }

    public function checkCategoryName() {

        if($this->findCategoryByName()) {

            Flash::addMessage('Kategoria już istnieje', Flash::DANGER);
            return false;
        }

        else {

            $this->addNewIncomeCategory();
        }
    }

    public function addNewIncomeCategory() {

        // echo $this->newCategoryName;
        // exit;

        $sql = 'INSERT INTO income_categories VALUES(:id_categories, :id_useres, :income_category)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_categories', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':id_useres', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':income_category', lcfirst($this->newCategoryName), PDO::PARAM_STR);

        return $stmt->execute();
    }



    
}