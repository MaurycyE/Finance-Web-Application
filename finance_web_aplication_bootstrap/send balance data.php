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

    $selectedPeriodOfTime=$_POST['periodOfTime'];
    

    if($selectedPeriodOfTime=="Bieżący miesiąc") {
        $userQuery = $databaseConnection->prepare("SELECT * FROM incomes WHERE YEAR(income_date)=YEAR(CURRENT_DATE())
        AND MONTH(income_date)=MONTH(CURRENT_DATE()) AND id_users=:idLoggedUser");
        $userQuery->bindValue(':idLoggedUser', $_SESSION['id_users'], PDO::PARAM_INT );
        $userQuery->execute();
        $result = $userQuery->fetchAll();
        $_SESSION["result"]=$result;

        header("Location: view balance sheet.php");
        exit();
    }


}

catch (PDOException $error) {
    echo $error->getMessage();
    exit('Darabase error');
}