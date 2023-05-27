<?php

namespace App\Models;

use PDO;

class Balance extends \Core\Model {

    public function __construct($selectedOption) {

        if(isset($selectedOption["periodOfTime"])) {

            $this->selectedPeriodOfTime=$selectedOption["periodOfTime"];
        }
        // else if (isset($_SESSION["sumResults"]['selectedOption'])){

        //     if($_SESSION["sumResults"]['selectedOption']['Poprzedni miesiąc']=='selected') {

        //         $selectedPeriodOfTimeAfterEdit = 'Poprzedni miesiąc';
        //     }
            
        //     else if($_SESSION["sumResults"]['selectedOption']['Bieżący rok']=='selected') {

        //         $selectedPeriodOfTimeAfterEdit = 'Bieżący rok';
        //     }

        //     else if($_SESSION["sumResults"]['selectedOption']['Niestandardowe']=='selected') {

        //         $selectedPeriodOfTimeAfterEdit = 'Niestandardowe';
        //     }

        //     $this->selectedPeriodOfTime = $selectedPeriodOfTimeAfterEdit;

        // }


        else if (isset($_SESSION['testScope'])) {

            $this->selectedPeriodOfTime=$_SESSION['testScope'];
            if($_SESSION['testScope']=="Niestandardowe") {

                $this->firstNotStandardDate = $_SESSION['firstNotStandardDate'];
                $this->secondNotStandardDate = $_SESSION['secondNotStandardDate'];
            }
        }

        else 
            $this->selectedPeriodOfTime = "Bieżący miesiąc";

        if(isset($selectedOption["firstNotStandardDate"])){

            $this->firstNotStandardDate = $selectedOption["firstNotStandardDate"];
            $_SESSION['firstNotStandardDate'] = $selectedOption["firstNotStandardDate"];
            $this->selectedPeriodOfTime = "Niestandardowe";
        }

        if(isset($selectedOption["secondNotStandardDate"])) {

            $this->secondNotStandardDate = $selectedOption["secondNotStandardDate"];
            $_SESSION['secondNotStandardDate'] = $selectedOption["secondNotStandardDate"];
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

    public function getGroupedIncomeOrExpensesCategories($incomeOrExpense, $tableName) {

        $db = static::getDB();

        switch($this->selectedPeriodOfTime) {

            case 'Bieżący miesiąc':
                $sql = "SELECT ".$incomeOrExpense."_category, SUM(".$incomeOrExpense."_amout) AS ".$incomeOrExpense."_sum_of_categories FROM $tableName, ".$incomeOrExpense."_categories
                WHERE YEAR(".$incomeOrExpense."_date)=YEAR(CURRENT_DATE()) AND MONTH(".$incomeOrExpense."_date) = MONTH(CURRENT_DATE()) AND $tableName.id_users=:idLoggedUser
                AND id_users_".$tableName."_categories=id_categories AND $tableName.id_users=".$incomeOrExpense."_categories.id_users GROUP BY ".$incomeOrExpense."_category
                ORDER BY ".$incomeOrExpense."_sum_of_categories DESC";
                break;

            case 'Poprzedni miesiąc':
                $sql = "SELECT ".$incomeOrExpense."_category, SUM(".$incomeOrExpense."_amout) AS ".$incomeOrExpense."_sum_of_categories FROM $tableName, ".$incomeOrExpense."_categories 
                WHERE ".$incomeOrExpense."_date BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01 00:00:00') AND 
                DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d 23:59:59') AND $tableName.id_users=:idLoggedUser 
                AND id_users_".$tableName."_categories=id_categories AND $tableName.id_users=".$incomeOrExpense."_categories.id_users GROUP BY ".$incomeOrExpense."_category 
                ORDER BY ".$incomeOrExpense."_sum_of_categories DESC";
                break;
            
            case 'Bieżący rok':
                $sql = "SELECT ".$incomeOrExpense."_category, SUM(".$incomeOrExpense."_amout) AS ".$incomeOrExpense."_sum_of_categories
                FROM $tableName, ".$incomeOrExpense."_categories WHERE YEAR(".$incomeOrExpense."_date)=YEAR(CURRENT_DATE())  AND $tableName.id_users=:idLoggedUser 
                AND id_users_".$tableName."_categories=id_categories AND $tableName.id_users=".$incomeOrExpense."_categories.id_users 
                GROUP BY ".$incomeOrExpense."_category ORDER BY ".$incomeOrExpense."_sum_of_categories DESC";
                break;
            
            case 'Niestandardowe':
                $sql = "SELECT ".$incomeOrExpense."_category, SUM(".$incomeOrExpense."_amout) AS ".$incomeOrExpense."_sum_of_categories
                FROM $tableName, ".$incomeOrExpense."_categories WHERE ".$incomeOrExpense."_date>='$this->firstNotStandardDate' AND ".$incomeOrExpense."_date<='$this->secondNotStandardDate' 
                AND $tableName.id_users=:idLoggedUser AND id_users_".$tableName."_categories=id_categories AND $tableName.id_users=".$incomeOrExpense."_categories.id_users 
                GROUP BY ".$incomeOrExpense."_category ORDER BY ".$incomeOrExpense."_sum_of_categories DESC";
                break;

        }

        $stmt = $this->executeStatment($db, $sql);

        return $stmt->fetchAll();
    }

    protected function setSelectedAtribute() {

        $atributeSet;

        $_SESSION['testScope'] = $this->selectedPeriodOfTime;

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
        $_SESSION['groupExpenseResults'] = $this->getGroupedIncomeOrExpensesCategories('expense', 'expenses');
        $_SESSION['groupIncomeResults'] = $this->getGroupedIncomeOrExpensesCategories('income', 'incomes');


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

        $_SESSION['expenseChartData'] = $this->setChartData('groupExpenseResults', 'expense');
        $_SESSION['incomeChartData'] = $this->setChartData('groupIncomeResults', 'income');

        // echo $_SESSION['sumResults']['selectedOption']['Bieżący miesiąc'];
        // exit;


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

    public function setChartData($incomeOrExpenseData, $columnName) {

        $dataPoints = array();

        if((isset($_SESSION[$incomeOrExpenseData])) && (isset($_SESSION["sumResults"]))) {

            foreach($_SESSION[$incomeOrExpenseData] as $expenseGroup){

                array_push($dataPoints, array("label"=>$expenseGroup[$columnName.'_category'], 
                "y"=>round($expenseGroup[$columnName.'_sum_of_categories']/$_SESSION["sumResults"][$columnName.'Sum']["SUM(".$columnName."_amout)"]*100, 2)));
            }
         
        }

        return $dataPoints;
    }

    public static function getChartData($incomeOrExpenseData) {

        if(isset($_SESSION[$incomeOrExpenseData])) {

            $dataPoints = $_SESSION[$incomeOrExpenseData];

            unset($_SESSION[$incomeOrExpenseData]);

            return $dataPoints;
        }
    }

}