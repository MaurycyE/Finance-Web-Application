<?php

session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: index.php");
    exit();
}

$databaseConfig = require_once 'configFile.php';

try {
    $databaseConnection = new PDO(
        'mysql:host=' . $databaseConfig['host'] . ';dbname=' . $databaseConfig['database'] . ';charset=utf8',
        $databaseConfig['user'],
        $databaseConfig['password'],
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    if (isset($_POST['incomeAmout'])) {
        $idLoggedUser = $_SESSION['id_users'];
        $incomeAmout = $_POST['incomeAmout'];
        $incomeDate = $_POST['incomeDate'];
        $selectedCategory = $_POST['selectedCategory'];
        if (isset($_POST['commentary'])) $commentary = $_POST['commentary'];
        else $commentary = "";

        $userQuery = $databaseConnection->prepare('SELECT id_categories FROM income_categories WHERE income_category=:selectedCategory
        AND id_users=:idLoggedUser');
        $userQuery->bindValue(':selectedCategory', $selectedCategory, PDO::PARAM_STR);
        $userQuery->bindValue(':idLoggedUser', $idLoggedUser, PDO::PARAM_INT);
        $userQuery->execute();



        $idOfSelectedCategory = $userQuery->fetch();

        $userQuery = $databaseConnection->prepare("INSERT INTO incomes VALUES(:idIncome, :idLoggedUser, :idOfSelectedCategory,
        :incomeAmout, :incomeDate, :commentary)");
        $userQuery->bindValue(':idIncome', NULL, PDO::PARAM_NULL);
        $userQuery->bindValue(':idLoggedUser', $idLoggedUser, PDO::PARAM_INT);
        $userQuery->bindValue(':idOfSelectedCategory', $idOfSelectedCategory['id_categories'], PDO::PARAM_INT);
        $userQuery->bindValue(':incomeAmout', $incomeAmout, PDO::PARAM_INT);
        $userQuery->bindValue(':incomeDate', $incomeDate, PDO::PARAM_STR);
        $userQuery->bindValue(':commentary', $commentary, PDO::PARAM_STR);
        $userQuery->execute();



        $_SESSION['recordSuccessfullyAdded'] = true;
        header('Location: add income.php');
        exit();
    }

    if (isset($_POST['expenseAmout'])) {
        $idLoggedUser = $_SESSION['id_users'];
        $expenseAmout = $_POST['expenseAmout'];
        $expenseDate = $_POST['expenseDate'];
        $selectedExpenseCategory = $_POST['selectedExpenseCategory'];
        $selectedPaymentCategory = $_POST['selectedPaymentCategory'];

        if (isset($_POST['expenseCommentary'])) $expenseDescription = $_POST['expenseCommentary'];
        else $expenseDescription = "";
        $expenseCategoryColumnName[0] = "id_categories";
        $expenseCategoryColumnName[1] = "expense_categories";
        $expenseCategoryColumnName[2] = "expense_category";
        $expensePaymentColumnName[0] = "id_payment";
        $expensePaymentColumnName[1] = "expense_payment";
        $expensePaymentColumnName[2] = "expense_payment_method";

        function findIdOfSelectedCategory($tableColumnName, $selectedCategory)
        {
            global $databaseConnection, $idLoggedUser;
            $userQuery = $databaseConnection->prepare("SELECT $tableColumnName[0] FROM $tableColumnName[1] WHERE $tableColumnName[2]=:selectedCategory
                AND id_users=:idLoggedUser");
            $userQuery->bindValue(':selectedCategory', $selectedCategory, PDO::PARAM_STR);
            $userQuery->bindValue(':idLoggedUser', $idLoggedUser, PDO::PARAM_INT);
            $userQuery->execute();

            return $userQuery->fetch();
        }

        $idOfSelectedCategory = findIdOfSelectedCategory($expenseCategoryColumnName, $selectedExpenseCategory);
        $idOfSelectedMethod = findIdOfSelectedCategory($expensePaymentColumnName, $selectedPaymentCategory);

        $userQuery = $databaseConnection->prepare("INSERT INTO expenses VALUES(:idExpense, :idLoggedUser, :idSelectedExpenseCategory,
        :selectedPaymentCategory, :expenseAmout, :expenseDate, :commentary)");
        $userQuery->bindValue(':idExpense', NULL, PDO::PARAM_NULL);
        $userQuery->bindValue(':idLoggedUser', $idLoggedUser, PDO::PARAM_INT);
        $userQuery->bindValue(':idSelectedExpenseCategory', $idOfSelectedCategory['id_categories'], PDO::PARAM_INT);
        $userQuery->bindValue(':selectedPaymentCategory', $idOfSelectedMethod['id_payment'], PDO::PARAM_INT);
        $userQuery->bindValue(':expenseAmout', $expenseAmout, PDO::PARAM_INT);
        $userQuery->bindValue(':expenseDate', $expenseDate, PDO::PARAM_STR);
        $userQuery->bindValue(':commentary', $expenseDescription, PDO::PARAM_STR);
        $userQuery->execute();

        $_SESSION['recordSuccessfullyAdded'] = true;
        header('Location: add expense.php');
        exit();
    }
} catch (PDOException $error) {
    echo $error->getMessage();
    exit('Database error');
}
