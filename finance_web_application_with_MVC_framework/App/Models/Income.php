<?php

namespace App\Models;

use PDO;
use \App\Models\Expense;

class Income extends \Core\Model {

    public function __construct($data=[]) {

        foreach($data as $key => $value) {

            $this->$key = $value;
        }
    }

    public function saveRecord() {

        $idOfSelectedIncomeCategory = Expense::findIdOfSelectedCategory($_POST['selectedCategory'], [
            "id" => 'id_categories',
            "tableName" => 'income_categories',
            "columnName" => 'income_category'
        ]);

        $db = static::getDB();
        $sql = 'INSERT INTO incomes VALUES(:idIncomes, :idLoggedUser, :idSelectedIncomeCategory,
        :incomeAmout, :incomeDate, :commentary)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idIncomes', NULL, PDO::PARAM_NULL);
        $stmt->bindValue(':idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':idSelectedIncomeCategory', $idOfSelectedIncomeCategory['id_categories'], PDO::PARAM_INT);
        $stmt->bindValue(':incomeAmout', $this->incomeAmout, PDO::PARAM_INT);
        $stmt->bindValue(':incomeDate', $this->incomeDate, PDO::PARAM_STR);
        $stmt->bindValue(':commentary', $this->commentary, PDO::PARAM_STR);

        return $stmt->execute();

    }

}