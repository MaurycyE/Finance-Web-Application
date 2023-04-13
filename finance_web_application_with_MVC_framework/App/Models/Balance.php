<?php

namespace App\Models;

use PDO;

class Balance extends \Core\Model {

    public function __construct($selectedOption) {

        $this->selectedPeriodOfTime=$selectedOption["periodOfTime"];
        
    }

    public function sumAmoutOfIncomeOrExpense($incomeOrExpenseAmout, $incomeOrExpenseTable, $incomeOrExpenseDate) {

        //if($this->selectedPeriodOfTime =='Bieżący miesiąc') 

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

           case 'Bieżący miesiąc':
                $sql = "SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpenseTable WHERE YEAR($incomeOrExpenseDate) = YEAR(CURRENT_DATE())
                AND MONTH($incomeOrExpenseDate) = MONTH(CURRENT_DATE()) AND id_users=:idLoggedUser";
                break;

        }


        $stmt = $db->prepare($sql);
        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch();
        
    }

    public function getIncomeResult() {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT * FROM incomes, income_categories WHERE YEAR(income_date) = YEAR(CURRENT_DATE())
                AND MONTH(income_date) = MONTH(CURRENT_DATE()) AND incomes.id_users=:idLoggedUser
                AND incomes.id_users=income_categories.id_users AND id_users_incomes_categories=id_categories
                ORDER BY income_date DESC";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getExpenseResult() {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT * FROM expenses, expense_categories, expense_payment WHERE YEAR(expense_date) = YEAR(CURRENT_DATE())
                AND MONTH(expense_date) = MONTH(CURRENT_DATE()) AND expenses.id_users=:idLoggedUser
                AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
                AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
                ORDER BY expense_date DESC";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getGroupedExpensesCategories() {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT expense_category, SUM(expense_amout) AS expense_sum_of_categories FROM expenses, expense_categories
                WHERE YEAR(expense_date)=YEAR(CURRENT_DATE()) AND MONTH(expense_date) = MONTH(CURRENT_DATE()) AND expenses.id_users=:idLoggedUser
                AND id_users_expenses_categories=id_categories AND expenses.id_users=expense_categories.id_users GROUP BY expense_category
                ORDER BY expense_sum_of_categories DESC";
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllResults() {

        $incomeResults = $this->getIncomeResult();
        $expenseResults = $this->getExpenseResult();
        $incomeSum = $this->sumAmoutOfIncomeOrExpense('income_amout', 'incomes', 'income_date');
        $expenseSum = $this->sumAmoutOfIncomeOrExpense('expense_amout', 'expenses', 'expense_date');
        $difference = $incomeSum["SUM(income_amout)"] - $expenseSum["SUM(expense_amout)"];

        $_SESSION['expenseResults'] = $expenseResults;
        $_SESSION['incomeResults'] = $incomeResults;
        $balanceColor = $this->setBalanceColor($difference);

        $_SESSION['sumResults'] = [

            //'incomeResults' => $incomeResults,
            //'expenseResults' => $expenseResults,
            'incomeSum' => $incomeSum,
            'expenseSum' => $expenseSum,
            'difference' => $difference,
            'balanceColor' => $balanceColor
        ];

    }

    protected function setBalanceColor($difference) {

        if($difference>=0) {
            $balanceColor = 'success';
        }

        else 
        $balanceColor = 'danger';

        return $balanceColor;
    }

    public static function getResultsToShow($dataSet) {

        if(isset($_SESSION[$dataSet])) {

        $balanceData = $_SESSION[$dataSet];
        unset($_SESSION[$dataSet]);

        return $balanceData;
        }
    }

}