<?php

session_start();

if((!isset($_POST['login']))||(!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";

mysqli_report(MYSQLI_REPORT_STRICT);

try{
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->connect_errno!=0){
        throw new Exception(mysqli_connect_errno());
    }

    else {
        $login=$_POST['login'];
        $password=$_POST['password'];

        $login = htmlentities($login, ENT_QUOTES, "UFT-8");
        $password = htmlentities($password, ENT_QUOTES, "UFT-8");

        if($result=$connection->query(sprintf("SELECT * FROM users WHERE user_email='%s'",
        mysqli_real_escape_string($connection, $login),
        ))) {
            if(!$result) throw new Exception ($connection->error);

            $usersFound=$result->num_rows;
        if($usersFound>0) {

                $row=$result->fetch_assoc();

            if(password_verify($password, $row['user_password'])){

                $_SESSION['isLoggedIn']=true;
                
                $_SESSION['id_users']=$row['id_users'];
                $_SESSION['user_name']=$row['user_name'];
                $_SESSION['user_password']=$row['user_password'];
                $_SESSION['user_email']=$row['user_email'];

                unset($_SESSION['error']);

                $result->free();

                header("Location: main menu.php");

            }
            else {

                $_SESSION['error']= '<span style="color:red">Coś poszło nie tak.. login lub hasło</span>';
                header('Location:log in.php');
            }
        }
        else {
            $_SESSION['error']= '<span style="color:red">Coś poszło nie tak.. login lub hasło</span>';
                header('Location:log in.php');
        }

    }
        $connection->close();
    }
}
catch (Exception $e) {
    echo '<span class="text-danger font-monospace" > Błąd serwera! </span>';
}

    ?>