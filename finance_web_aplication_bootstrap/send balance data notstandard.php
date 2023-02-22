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


    if($_POST['firstNotStandardDate']){

       $firstNotStandardDate = $_POST['firstNotStandardDate'];
       $secondNotStandardDate = $_POST['secondNotStandardDate'];
    }

    function sumAmoutOfIncomeOrExpense($incomeOrExpenseAmout, $incomeOrExpanseTable, $incomeOrExpenseDate) {
        global $databaseConnection, $firstNotStandardDate, $secondNotStandardDate;

        $userQuery = $databaseConnection->prepare("SELECT SUM($incomeOrExpenseAmout) FROM $incomeOrExpanseTable WHERE 
        $incomeOrExpenseDate>='$firstNotStandardDate' AND $incomeOrExpenseDate<='$secondNotStandardDate' AND id_users=:idLoggedUser");
        $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT);
        $userQuery->execute();

        return $userQuery->fetch();
    }

    //incomes
    $userQuery = $databaseConnection->prepare("SELECT * FROM incomes, income_categories WHERE income_date>='$firstNotStandardDate' 
    AND income_date<='$secondNotStandardDate' AND incomes.id_users=:idLoggedUser AND incomes.id_users=income_categories.id_users 
    AND id_users_incomes_categories=id_categories ORDER BY income_date DESC");
    $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT );
    $userQuery->execute();

    $_SESSION["incomeResult"] = $userQuery->fetchAll();
    $_SESSION["incomeSum"] = sumAmoutOfIncomeOrExpense("income_amout", "incomes", "income_date");

    //expenses
    $userQuery = $databaseConnection->prepare("SELECT * FROM expenses, expense_categories, expense_payment WHERE
    expense_date>='$firstNotStandardDate' AND expense_date<='$secondNotStandardDate' AND expenses.id_users=:idLoggedUser
    AND expenses.id_users=expense_categories.id_users AND expenses.id_users=expense_payment.id_users
    AND id_users_expenses_categories=id_categories AND expenses.id_payment=expense_payment.id_payment
    ORDER BY expense_date DESC");
    $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT);
    $userQuery->execute();

    $_SESSION["expenseResult"] = $userQuery->fetchAll();
    $_SESSION["expenseSum"] = sumAmoutOfIncomeOrExpense("expense_amout", "expenses", "expense_date");

    //grouped expenses categories
    $userQuery = $databaseConnection->prepare("SELECT expense_category, SUM(expense_amout) AS expense_sum_of_categories
    FROM expenses, expense_categories WHERE expense_date>='$firstNotStandardDate' AND expense_date<='$secondNotStandardDate' 
    AND expenses.id_users=:idLoggedUser AND id_users_expenses_categories=id_categories AND expenses.id_users=expense_categories.id_users 
    GROUP BY expense_category ORDER BY expense_sum_of_categories DESC");
    $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT);
    $userQuery->execute();
    $_SESSION['groupResults'] = $userQuery->fetchAll();

    $_SESSION['selectedCurrentMonthOption'] = "";
    $_SESSION['selectedPreviousMonthOption'] = "";
    $_SESSION['selectedCurrentYearOption'] = "";
    $_SESSION['selectedNotStandardOption'] = "selected";

    header('Location:view balance sheet.php');
    exit();
}

catch (PDOException $error) {
    echo $error->getMessage();
    exit('Database error');
}