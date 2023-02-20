<?php

session_start();
if(!isset($_SESSION["isLoggedIn"])) {
    header("Location: index.php");
    exit();
}

$databaseConfig = require_once 'configFile.php';

try {
    $databaseConnection = new PDO ('mysql:host='.$databaseConfig['host'].';dbname='.$databaseConfig['database'].';charset=utf8',
    $databaseConfig['user'], $databaseConfig['password'], [
        PDO::ATTR_EMULATE_PREPARES=>false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $_SESSION["selectedPeriodOfTime"]=$_POST['periodOfTime'];

    if(!isset($_SESSION["selectedPeriodOfTime"]))
        $_SESSION["selectedPeriodOfTime"]="Bieżący miesiąc";

    if($_SESSION["selectedPeriodOfTime"]=="Bieżący miesiąc") {

            function sumAmoutOfIncomeOrExpense($incomeOrExpenseAmout, $incomeOrExpanseTable, $incomeOrExpenseDate) {
                global $databaseConnection;

                $userQuery = $databaseConnection->prepare("SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpanseTable WHERE 
                YEAR($incomeOrExpenseDate)=YEAR(CURRENT_DATE()) AND MONTH($incomeOrExpenseDate)=MONTH(CURRENT_DATE()) AND id_users=:idLoggedUser");
                $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT);
                $userQuery->execute();

                return $userQuery->fetch();
            }

        $userQuery = $databaseConnection->prepare("SELECT * FROM incomes, income_categories WHERE YEAR(income_date)=YEAR(CURRENT_DATE())
        AND MONTH(income_date)=MONTH(CURRENT_DATE()) AND incomes.id_users=:idLoggedUser 
        AND incomes.id_users=income_categories.id_users 
        AND id_users_incomes_categories=id_categories ORDER BY income_date DESC");
        $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT );
        $userQuery->execute();

        $_SESSION["incomeResult"] = $userQuery->fetchAll();
        $_SESSION["incomeSum"] = sumAmoutOfIncomeOrExpense("income_amout", "incomes", "income_date");

        $userQuery = $databaseConnection->prepare("SELECT * FROM expenses, expense_categories, expense_payment WHERE
        YEAR(expense_date)=YEAR(CURRENT_DATE()) AND MONTH(expense_date)=MONTH(CURRENT_DATE()) AND expenses.id_users=:idLoggedUser
        AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
        AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
        ORDER BY expense_date DESC");
        $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT);
        $userQuery->execute();

        $_SESSION["expenseResult"] = $userQuery->fetchAll();
        $_SESSION["expenseSum"] = sumAmoutOfIncomeOrExpense("expense_amout", "expenses", "expense_date");

        //$_SESSION["differenceBeetwenIncomeAndExpense"][0] = $_SESSION["incomeSum"][0] - $_SESSION["expenseSum"][0];

        header("Location: view balance sheet.php");
        exit();
    }


}

catch (PDOException $error) {
    echo $error->getMessage();
    exit('Darabase error');
}