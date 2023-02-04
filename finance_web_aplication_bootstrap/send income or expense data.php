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

}

catch (PDOException $error) {
    echo $error->getMessage();
    exit('Darabase error');
}

$incomeAmout = $_POST['incomeAmout'];
$incomeDate = $_POST['incomeDate'];
$selectedCategory = $_POST['selectedCategory'];
if(isset($_POST['commentary'])) $commentary = $_POST['commentary']; 
else $commentary="";

//$query = $databaseConfig->prepare('INSERT INTO ')







