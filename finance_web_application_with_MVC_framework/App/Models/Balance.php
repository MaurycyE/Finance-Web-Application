<?php

namespace App\Models;

use PDO;

class Balance extends \Core\Model {

    public function __construct($selectedOption) {

        if(isset($selectedOption["periodOfTime"])) {

            $this->selectedPeriodOfTime=$selectedOption["periodOfTime"];
        }
        else 
            $this->selectedPeriodOfTime = "Bieżący miesiąc";

        if(isset($selectedOption["firstNotStandardDate"])){

            $this->firstNotStandardDate = $selectedOption["firstNotStandardDate"];
            $this->selectedPeriodOfTime = "Niestandardowe";
        }

        if(isset($selectedOption["secondNotStandardDate"])) {

            $this->secondNotStandardDate = $selectedOption["secondNotStandardDate"];
        }

    }

    protected function executeStatment($db, $sql) {

        $stmt = $db->prepare($sql);
        $stmt->bindValue('idLoggedUser', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    protected function sumAmoutOfIncomeOrExpense($incomeOrExpenseAmout, $incomeOrExpenseTable, $incomeOrExpenseDate) {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

           case 'Bieżący miesiąc':
                $sql = "SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpenseTable WHERE YEAR($incomeOrExpenseDate) = YEAR(CURRENT_DATE())
                AND MONTH($incomeOrExpenseDate) = MONTH(CURRENT_DATE()) AND id_users=:idLoggedUser";
                break;
            
            case 'Poprzedni miesiąc':
                $sql = "SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpenseTable WHERE $incomeOrExpenseDate BETWEEN 
                DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00') AND DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59')
                AND id_users=:idLoggedUser";
                break;
            
            case 'Bieżący rok':
                $sql = "SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpenseTable WHERE 
                YEAR($incomeOrExpenseDate)=YEAR(CURRENT_DATE()) AND id_users=:idLoggedUser";
                break;
            
            case 'Niestandardowe':
                $sql = "SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpenseTable WHERE 
                $incomeOrExpenseDate>='$this->firstNotStandardDate' AND $incomeOrExpenseDate<='$this->secondNotStandardDate' AND id_users=:idLoggedUser";
                break;

        }

        $stmt = $this->executeStatment($db, $sql);
        
        return $stmt->fetch();
        
    }

    protected function getIncomeResult() {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT * FROM incomes, income_categories WHERE YEAR(income_date) = YEAR(CURRENT_DATE())
                AND MONTH(income_date) = MONTH(CURRENT_DATE()) AND incomes.id_users=:idLoggedUser
                AND incomes.id_users=income_categories.id_users AND id_users_incomes_categories=id_categories
                ORDER BY income_date DESC";
                break;
            
            case 'Poprzedni miesiąc':
                $sql = "SELECT * FROM incomes, income_categories WHERE income_date BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00')
                AND DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59') AND incomes.id_users=:idLoggedUser 
                AND incomes.id_users=income_categories.id_users AND id_users_incomes_categories=id_categories ORDER BY income_date DESC";
                break;

            case 'Bieżący rok':
                $sql = "SELECT * FROM incomes, income_categories WHERE YEAR(income_date)=YEAR(CURRENT_DATE())
                AND incomes.id_users=:idLoggedUser AND incomes.id_users=income_categories.id_users 
                AND id_users_incomes_categories=id_categories ORDER BY income_date DESC";
                break;
            
            case 'Niestandardowe':
                $sql = "SELECT * FROM incomes, income_categories WHERE income_date>='$this->firstNotStandardDate' 
                AND income_date<='$this->secondNotStandardDate' AND incomes.id_users=:idLoggedUser AND incomes.id_users=income_categories.id_users 
                AND id_users_incomes_categories=id_categories ORDER BY income_date DESC";
                break;
        }

        $stmt = $this->executeStatment($db, $sql);

        return $stmt->fetchAll();
    }

    protected function getExpenseResult() {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT * FROM expenses, expense_categories, expense_payment WHERE YEAR(expense_date) = YEAR(CURRENT_DATE())
                AND MONTH(expense_date) = MONTH(CURRENT_DATE()) AND expenses.id_users=:idLoggedUser
                AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
                AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
                ORDER BY expense_date DESC";
                break;
            
            case 'Poprzedni miesiąc':
                $sql = "SELECT * FROM expenses, expense_categories, expense_payment WHERE expense_date
                BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00')
                AND DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59') AND expenses.id_users=:idLoggedUser
                AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
                AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
                ORDER BY expense_date DESC";
                break;
            
            case 'Bieżący rok':
                $sql = "SELECT * FROM expenses, expense_categories, expense_payment WHERE
                YEAR(expense_date)=YEAR(CURRENT_DATE()) AND expenses.id_users=:idLoggedUser
                AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
                AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
                ORDER BY expense_date DESC";
                break;
            
            case 'Niestandardowe':
                $sql = "SELECT * FROM expenses, expense_categories, expense_payment WHERE
                expense_date>='$this->firstNotStandardDate' AND expense_date<='$this->secondNotStandardDate' AND expenses.id_users=:idLoggedUser
                AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
                AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
                ORDER BY expense_date DESC";
                break;

        }

        $stmt = $this->executeStatment($db, $sql);

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
                break;

            case 'Poprzedni miesiąc':
                $sql = "SELECT expense_category, SUM(expense_amout) AS expense_sum_of_categories FROM expenses, expense_categories 
                WHERE expense_date BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00') AND 
                DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59') AND expenses.id_users=:idLoggedUser 
                AND id_users_expenses_categories=id_categories AND expenses.id_users=expense_categories.id_users GROUP BY expense_category 
                ORDER BY expense_sum_of_categories DESC";
                break;
            
            case 'Bieżący rok':
                $sql = "SELECT expense_category, SUM(expense_amout) AS expense_sum_of_categories
                FROM expenses, expense_categories WHERE YEAR(expense_date)=YEAR(CURRENT_DATE())  AND expenses.id_users=:idLoggedUser 
                AND id_users_expenses_categories=id_categories AND expenses.id_users=expense_categories.id_users 
                GROUP BY expense_category ORDER BY expense_sum_of_categories DESC";
                break;
            
            case 'Niestandardowe':
                $sql = "SELECT expense_category, SUM(expense_amout) AS expense_sum_of_categories
                FROM expenses, expense_categories WHERE expense_date>='$this->firstNotStandardDate' AND expense_date<='$this->secondNotStandardDate' 
                AND expenses.id_users=:idLoggedUser AND id_users_expenses_categories=id_categories AND expenses.id_users=expense_categories.id_users 
                GROUP BY expense_category ORDER BY expense_sum_of_categories DESC";
                break;

        }

        $stmt = $this->executeStatment($db, $sql);

        return $stmt->fetchAll();
    }

    protected function setSelectedAtribute() {

        $atributeSet;

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $atributeSet = [
                    'Bieżący miesiąc' => 'selected',
                    'Poprzedni miesiąc' => '',
                    'Bieżący rok' => '',
                    'Niestandardowe' => ''
                ];
                break;

            case 'Poprzedni miesiąc':
                $atributeSet = [
                    'Bieżący miesiąc' => '',
                    'Poprzedni miesiąc' => 'selected',
                    'Bieżący rok' => '',
                    'Niestandardowe' => ''
                ];
                break;

            case 'Bieżący rok':
                $atributeSet = [
                    'Bieżący miesiąc' => '',
                    'Poprzedni miesiąc' => '',
                    'Bieżący rok' => 'selected',
                    'Niestandardowe' => ''
                ];
                break;

            case 'Niestandardowe':
                $atributeSet = [
                    'Bieżący miesiąc' => '',
                    'Poprzedni miesiąc' => '',
                    'Bieżący rok' => '',
                    'Niestandardowe' => 'selected'
                ];
                break;
        }

        return $atributeSet;
    }

    public function getAllResults() {

        $incomeResults = $this->getIncomeResult();
        $expenseResults = $this->getExpenseResult();
        $incomeSum = $this->sumAmoutOfIncomeOrExpense('income_amout', 'incomes', 'income_date');
        $expenseSum = $this->sumAmoutOfIncomeOrExpense('expense_amout', 'expenses', 'expense_date');
        $difference = $incomeSum["SUM(income_amout)"] - $expenseSum["SUM(expense_amout)"];
        $selectedOption = $this->setSelectedAtribute();
        $_SESSION['groupResults'] = $this->getGroupedExpensesCategories();


        $_SESSION['expenseResults'] = $expenseResults;
        $_SESSION['incomeResults'] = $incomeResults;

        $balanceColor = $this->setBalanceColor($difference);

        $_SESSION['sumResults'] = [

            'incomeSum' => $incomeSum,
            'expenseSum' => $expenseSum,
            'difference' => $difference,
            'balanceColor' => $balanceColor,
            'selectedOption' => $selectedOption
        ];

        $_SESSION['chartData'] = $this->setChartData();

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

    public function setChartData() {

        $dataPoints = array();

        if((isset($_SESSION['groupResults'])) && (isset($_SESSION["sumResults"]))) {

        foreach($_SESSION['groupResults'] as $expenseGroup){

            array_push($dataPoints, array("label"=>$expenseGroup['expense_category'], 
            "y"=>round($expenseGroup['expense_sum_of_categories']/$_SESSION["sumResults"]['expenseSum']["SUM(expense_amout)"]*100, 2)));
        }
         
        }

        return $dataPoints;
    }

    public static function getChartData() {

        if(isset($_SESSION['chartData'])) {

            $dataPoints = $_SESSION['chartData'];

            unset($_SESSION['chartData']);
            //
            //var_dump($dataPoints);

            return $dataPoints;
        }
    }


}