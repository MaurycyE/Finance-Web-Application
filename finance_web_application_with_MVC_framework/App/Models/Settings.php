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

        //return $stmt->execute();

        $stmt->execute();
        // var_dump($stmt->fetchAll());
        // exit;
        return $stmt->fetchAll();

    }

    public function checkCategoryName() {

        if($this->findCategoryByName()) {

            Flash::addMessage('Kategoria już istnieje', Flash::DANGER);
            return false;
        }

        else {

            if($this->addNewIncomeCategory()) {
                
                return true;
            }
        }
    }

    public function addNewIncomeCategory() {

        $sql = 'INSERT INTO income_categories VALUES(:id_categories, :id_useres, :income_category)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_categories', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':id_useres', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':income_category', lcfirst($this->newCategoryName), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function deleteCategory() {

        $sql = "DELETE FROM income_categories WHERE id_users = :idLoggedUser AND income_category = :selectedCategory";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':selectedCategory', $this->selectedCategoryToDelete, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function findIdCategory() {

        $sql = "SELECT id_categories FROM income_categories WHERE income_category=:categoryName AND id_users=:idLoggedUser";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryName', $this->selectedCategory, PDO::PARAM_STR);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
        
    }

    public function deleteRelatedRecords() {

        // $this->idCategoryToDelete = $this->findIdCategoryToDelete();

        $sql = "DELETE FROM incomes WHERE id_users = :idLoggedUser AND id_users_incomes_categories = :idCategoryToDelete";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idCategoryToDelete', $this->idCategoryToDelete["id_categories"], PDO::PARAM_INT);
        
        return $stmt->execute();

    }

    public function renameCategory() {

        $this->idCategory = $this->findIdCategory();

        $sql = "UPDATE income_categories SET income_category = :newCategoryName WHERE id_users = :idLoggedUser
        AND id_categories = :idCategory";
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':newCategoryName', lcfirst($this->newCategoryName), PDO::PARAM_STR);
        $stmt->bindValue(':idCategory', $this->idCategory["id_categories"], PDO::PARAM_INT);

        return $stmt->execute();
        
    }


    
}