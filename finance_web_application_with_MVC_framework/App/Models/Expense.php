<?php

namespace App\Models;

use PDO;
use \App\Config;
use \App\Models\User;

class Expense extends \Core\Model {

    public function __construct( $data = [] ) {

        foreach ($data as $key => $value) {

            $this->$key = $value;
        }
    }

    protected function findIdOfSelectedCategory($selectedCategory, $databaseColumnName) {

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

        $idSelectedExpenseCategory = $this->findIdOfSelectedCategory($_POST['selectedExpenseCategory'], [
            "id" => 'id_categories',
            "tableName" => 'expense_categories',
            "columnName" => 'expense_category'
        ]);

        $idSelectedPaymentCategory = $this->findIdOfSelectedCategory($_POST['selectedPaymentCategory'], [
            "id" => 'id_payment',
            "tableName" => 'expense_payment',
            "columnName" => 'expense_payment_method'
        ]);

        $db = static::getDB();
        $sql = 'INSERT INTO expenses VALUES(:idExpense, :idLoggedUser, :idSelectedExpenseCategory, :idSelectedPaymentCategory,
        :expenseAmout, :expenseDate, :commentary)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idExpense', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idSelectedExpenseCategory', $idSelectedExpenseCategory['id_categories'], PDO::PARAM_INT);
        $stmt->bindValue(':idSelectedPaymentCategory', $idSelectedPaymentCategory['id_payment'], PDO::PARAM_INT);
        $stmt->bindValue(':expenseAmout', $this->expenseAmout, PDO::PARAM_INT);
        $stmt->bindValue(':expenseDate', $this->expenseDate, PDO::PARAM_STR);
        $stmt->bindValue(':commentary', $this->expenseCommentary, PDO::PARAM_STR);

        return $stmt->execute();

    }

}