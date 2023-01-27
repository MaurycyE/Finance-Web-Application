<?php

session_start();
require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name);
if($connection->connect_errno!=0){
    echo "Error: ".$connection->connect_errno;
}

else {
    $login=$_POST['login'];
    $password=$_POST['password'];

    $sql = "SELECT * FROM users WHERE user_email='$login' AND user_password='$password'";

    if($result=@$connection->query($sql)) {

        $usersFound=$result->num_rows;
        if($usersFound>0) {

            $_SESSION['isLoggedIn']=true;
            
            $row=$result->fetch_assoc();
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

    $connection->close();
}


?>