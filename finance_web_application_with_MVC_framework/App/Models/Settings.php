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

    static public function getIncomeExpenseCategories($columnName, $tableName) {

        if(isset($_SESSION['user_id'])) {
        $sql = "SELECT $columnName FROM $tableName WHERE id_users = :idLoggedUser";

        $db=static::getDB();
        $stmt = $db->prepare($sql);

        $stmt -> bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
        }
    }

    public function findCategoryByName() {

        switch($this->categoryType) {

            case 'incomeCategory':
                $sql = "SELECT * FROM income_categories WHERE id_users = :idLoggedUser AND income_category=:newUserCategory";
                break;

            case 'expenseCategory':
                $sql = "SELECT * FROM expense_categories WHERE id_users = :idLoggedUser AND expense_category=:newUserCategory";
                break;
            
            case 'paymentMethod':
                $sql = "SELECT * FROM expense_payment WHERE id_users = :idLoggedUser AND expense_payment_method=:newUserCategory";
                break;
        }

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

            if($this->addNewCategory()) {
                
                return true;
            }
        }
    }

    public function addNewCategory() {

        switch($this->categoryType) {

            case 'incomeCategory': 
                $sql = 'INSERT INTO income_categories VALUES(:id_categories, :id_useres, :category)';
                break;

            case 'expenseCategory': 
                $sql = 'INSERT INTO expense_categories VALUES(:id_categories, :id_useres, :category)';
                break;

            case 'paymentMethod': 
                $sql = 'INSERT INTO expense_payment VALUES(:id_categories, :id_useres, :category)';
                break;

        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_categories', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':id_useres', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':category', lcfirst($this->newCategoryName), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function deleteCategory() {

        switch($this->categoryType) {

            case 'incomeCategory':
                $sql = "DELETE FROM income_categories WHERE id_users = :idLoggedUser AND income_category = :selectedCategory";
                break;

            case 'expenseCategory':
                $sql = "DELETE FROM expense_categories WHERE id_users = :idLoggedUser AND expense_category = :selectedCategory";
                break;
            
            case 'paymentMethod':
                $sql = "DELETE FROM expense_payment WHERE id_users = :idLoggedUser AND expense_payment_method = :selectedCategory";
                break;

        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':selectedCategory', $this->selectedCategory, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function findIdCategory() {

        switch($this->categoryType) {

            case 'incomeCategory':
                $sql = "SELECT id_categories FROM income_categories WHERE income_category=:categoryName AND id_users=:idLoggedUser";
                break;
            
            case 'paymentMethod':
                $sql = "SELECT id_payment FROM expense_payment WHERE expense_payment_method=:categoryName AND id_users=:idLoggedUser";
                break;

            case 'expenseCategory':
                $sql = "SELECT id_categories FROM expense_categories WHERE expense_category=:categoryName AND id_users=:idLoggedUser";
                break;
        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':categoryName', $this->selectedCategory, PDO::PARAM_STR);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
        
    }

    public function deleteRelatedRecords() {

        // $this->idCategoryToDelete = $this->findIdCategoryToDelete();
        switch($this->categoryType){

            case 'incomeCategory':
                $sql = "DELETE FROM incomes WHERE id_users = :idLoggedUser AND id_users_incomes_categories = :idCategoryToDelete";
                break;
            
            case 'expenseCategory':
                $sql = "DELETE FROM expenses WHERE id_users = :idLoggedUser AND id_users_expenses_categories = :idCategoryToDelete";
                break;
            
            case 'paymentMethod':
                $sql = "DELETE FROM expenses WHERE id_users = :idLoggedUser AND id_payment = :idCategoryToDelete";
                break;
            
        }

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idCategoryToDelete', $this->idCategoryToDelete[0], PDO::PARAM_INT);
        
        return $stmt->execute();

    }

    public function renameCategory() {

        $this->idCategory = $this->findIdCategory();

        switch($this->categoryType) {

            case 'incomeCategory':
                $sql = "UPDATE income_categories SET income_category = :newCategoryName WHERE id_users = :idLoggedUser
                AND id_categories = :idCategory";
                break;

            case 'expenseCategory':
                $sql = "UPDATE expense_categories SET expense_category = :newCategoryName WHERE id_users = :idLoggedUser
                AND id_categories = :idCategory";
                break;

            case 'paymentMethod':
                $sql = "UPDATE expense_payment SET expense_payment_method = :newCategoryName WHERE id_users = :idLoggedUser
                AND id_payment = :idCategory";
                break;
        }
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':newCategoryName', lcfirst($this->newCategoryName), PDO::PARAM_STR);
        $stmt->bindValue(':idCategory', $this->idCategory[0], PDO::PARAM_INT);

        return $stmt->execute();
        
    }

    public function deleteAccount() {

        if($this->userConfirmation == "on") {

            $this->deleteUserDataFromExpenses();
            $this->deleteUserDataFromExpensesCategories();
            $this->deleteUserDataFromExpensesPayment();
            $this->deleteUserDataFromIncomes();
            $this->deleteUserDataFromIncomeCategories();

            $sql = "DELETE FROM users WHERE id_users = :idLoggedUser";

            return $this->executeQuery($sql);
        }

        return false;
    }

    protected function executeQuery($sql) {

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteUserDataFromExpenses() {

        $sql = "DELETE FROM expenses WHERE id_users = :idLoggedUser";
        $this->executeQuery($sql);

    }

    public function deleteUserDataFromExpensesCategories() {

        $sql = "DELETE FROM expense_categories WHERE id_users = :idLoggedUser";
        $this->executeQuery($sql);

    }

    public function deleteUserDataFromExpensesPayment() {

        $sql = "DELETE FROM expense_payment WHERE id_users = :idLoggedUser";
        $this->executeQuery($sql);

    }

    public function deleteUserDataFromIncomes() {

        $sql = "DELETE FROM incomes WHERE id_users = :idLoggedUser";
        $this->executeQuery($sql);

    }

    public function deleteUserDataFromIncomeCategories() {

        $sql = "DELETE FROM income_categories WHERE id_users = :idLoggedUser";
        $this->executeQuery($sql);

    }
    
}