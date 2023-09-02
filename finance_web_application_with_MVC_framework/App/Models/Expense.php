<?php

namespace App\Models;

use PDO;

class Expense extends \Core\Model {

    public function __construct( $data = [] ) {

        foreach ($data as $key => $value) {

            $this->$key = $value;
        }
    }

    public static function findIdOfSelectedCategory($selectedCategory, $databaseColumnName) {

        extract($databaseColumnName, EXTR_SKIP);

        $db = static::getDB();
        $sql = "SELECT $id FROM $tableName WHERE $columnName = :selectedCategory
        AND id_users = :idLoggedUser";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':selectedCategory', $selectedCategory, PDO::PARAM_STR);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch();

    }

    public function saveRecord() {

        $idSelectedExpenseCategory = static::findIdOfSelectedCategory($_POST['selectedExpenseCategory'], [
            "id" => 'id_categories',
            "tableName" => 'expense_categories',
            "columnName" => 'expense_category'
        ]);

        $idSelectedPaymentCategory = static::findIdOfSelectedCategory($_POST['selectedPaymentCategory'], [
            "id" => 'id_payment',
            "tableName" => 'expense_payment',
            "columnName" => 'expense_payment_method'
        ]);

        $limit = static::getLimit($_POST['selectedExpenseCategory']);

        $db = static::getDB();
        $sql = 'INSERT INTO expenses VALUES(:idExpense, :idLoggedUser, :idSelectedExpenseCategory, :idSelectedPaymentCategory,
        :expenseAmout, :expenseDate, :commentary, :setLimit)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idExpense', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idSelectedExpenseCategory', $idSelectedExpenseCategory['id_categories'], PDO::PARAM_INT);
        $stmt->bindValue(':idSelectedPaymentCategory', $idSelectedPaymentCategory['id_payment'], PDO::PARAM_INT);
        $stmt->bindValue(':expenseAmout', $this->expenseAmout, PDO::PARAM_STR);
        $stmt->bindValue(':expenseDate', $this->expenseDate, PDO::PARAM_STR);
        $stmt->bindValue(':commentary', $this->expenseCommentary, PDO::PARAM_STR);
        $stmt->bindValue(':setLimit', $limit, PDO::PARAM_STR);

        return $stmt->execute();

    }

    public static function getLimit($expenseCategory) {

        $db = static::getDB();
        $sql = "SELECT set_limit FROM expense_categories WHERE id_users = :idLoggedUser AND expense_category = :category";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':category', $expenseCategory, PDO::PARAM_STR);
        $stmt->execute();

        $limit = $stmt->fetch();

        return $limit["set_limit"];
    }

    public static function getExpensesSumInSelectedCategoryAndDate($expenseDate, $expenseCategory) {

        $idSelectedExpenseCategory = static::findIdOfSelectedCategory($expenseCategory, [
            "id" => 'id_categories',
            "tableName" => 'expense_categories',
            "columnName" => 'expense_category'
        ]);

        $db = static::getDB();
        $sql = "SELECT SUM(expense_amout) FROM expenses WHERE YEAR('$expenseDate') = YEAR(expense_date)
                AND MONTH('$expenseDate') = MONTH(expense_date) AND id_users=:idLoggedUser 
                AND id_users_expenses_categories = :idCategory";
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idCategory', $idSelectedExpenseCategory['id_categories'], PDO::PARAM_INT);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $sumAmout = $stmt->fetch();

        $sumAmout = floatval($sumAmout["SUM(expense_amout)"]);

        return $sumAmout;
    }

}