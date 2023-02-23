<?php
session_start();

unset($_SESSION['error']);

if((isset($_SESSION['isLoggedIn']))&&($_SESSION['isLoggedIn']==true)) {
    header('Location: main menu.php');
    exit();
}

if(isset($_POST['email'])) {

    $areAllRegistrationDataOk = true;
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $confirmPassword=$_POST['confirmPassword'];

    //checking username
    if((strlen($username)<3)||(strlen($username)>20)) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithUsername']="Nazwa użytkownika musi zawierać od 3 do 20 znaków";
    }

    if(ctype_alnum($username)==false) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithUsername']="Nazwa użytkownika może się składać tylko z liter i cyfr (bez polskich znaków)";
    }

    //checking email 
    $comparingEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($comparingEmail, FILTER_VALIDATE_EMAIL)==false)||($comparingEmail!=$email)) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithEmail']="Podaj prawidłowy email";
    }

    //checking password
    if((strlen($password)<8)||(strlen($password)>20)) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithPassword']="Hasło musi mieć co najmniej 8 znaków i nie więcej niż 20";
    }
    if($password!=$confirmPassword) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithPassword']="Hasła nie są takie same";
    }

    $passwordConvertedToHash=password_hash($password, PASSWORD_DEFAULT);

    $_SESSION['usernameWritenInForm']=$username;
    $_SESSION['emailWritenInForm']=$email;
    $_SESSION['passwordWritenInForm']=$password;
    $_SESSION['confirmPasswordWritenInForm']=$confirmPassword;

    require_once "connect.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $connection=new mysqli($host, $db_user, $db_password, $db_name);

    if($connection->connect_errno!=0){
        throw new Exeption (mysqli_connect_errno());
    }
    else {
        //checking if email exist
        $resultOfQuery = $connection->query("SELECT id_users FROM users WHERE user_email='$email'");

        if (!$resultOfQuery){
            throw new Exeption ($resultOfQuery->error);
        }
        $howManyEmailHasBeenFound = $resultOfQuery->num_rows;

        if($howManyEmailHasBeenFound>0) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithEmail']="Istnieje już konto przypisane do tego adresu";
        }
        //checking if username exist

        $resultOfQuery=$connection->query("SELECT id_users FROM users WHERE user_name='$username'");

        if (!$resultOfQuery){
            throw new Exeption ($resultOfQuery->error);
        }

        $howManyUsernamesHasBeenFound=$resultOfQuery->num_rows;

        if($howManyUsernamesHasBeenFound>0) {
        $areAllRegistrationDataOk=false;
        $_SESSION['errorWithUsername']="Nazwa użytkownika jest już zajęta";
        }

        function writeDefaultCategoriesToNewUser ($tableColumn, $tableWithDefaultValues, $targetTable) {
            global $connection, $userId;
            $resultOfQuery=$connection->query("SELECT $tableColumn FROM $tableWithDefaultValues");
                $rowFromDatabase=$resultOfQuery->fetch_all(MYSQLI_NUM);
                
                foreach($rowFromDatabase as $defaultCategory){
                $connection->query("INSERT INTO $targetTable VALUES (NULL, '$userId', 
                '$defaultCategory[0]')");
                }
        }

        if($areAllRegistrationDataOk==true){
            if($connection->query("INSERT INTO users VALUES(NULL, '$username', '$email', '$passwordConvertedToHash')")) {
                
                //przepisywanie tabeli default income/expense/payment categories
                $resultOfQuery=$connection->query("SELECT id_users FROM users WHERE user_name='$username'");
                $rowFromDatabase=$resultOfQuery->fetch_assoc();
                $userId = $rowFromDatabase['id_users'];

                writeDefaultCategoriesToNewUser('income_category', 'incomes_deafult_categories', 'income_categories');
            
                writeDefaultCategoriesToNewUser('expense_category', 'expense_deafult_categories', 'expense_categories');

                writeDefaultCategoriesToNewUser('expense_deafult_payment_method', 'expense_payment_deafult', 'expense_payment');
                
                $_SESSION['succesfullRegistration']= true;
  
                header('Location:log in.php');
            }
            else {
                throw new Exeption($connection->error);
            }
        }
        $resultOfQuery->free();
        $connection->close();
    }
    }
    catch(Exeptions $e) {
        echo '<span class="text-danger font-monospace">Coś poszło nie tak.. serwer nie odpowiada</span>';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration site</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">
</head>

<body>

    <div class="container-fluid bg-image  d-flex justify-content-center align-items-center"
        style="background-image: url(img/background_stars2.jpg); height: 100vh;">

        <main>

            <div class="row justify-content-center text-center">

                <div class="col-8 col-sm-10 bg-white rounded-5 shadow-lg border">

                    <h1 class=" h4 my-3 fw-bolder font-monospace mx-2 mt-4">
                        Zyskaj kontrolę nad swoimi wydatkami!
                    </h1>

                    <p class="h5 my-3 fw-bolder font-monospace">Zacznij od..</p>

                    <form method="post" class="m-4">

                        <div class="input-group mb-2">
                            <span class="input-group-text" id="username"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-file-person"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M12 1a1 1 0 0 1 1 1v10.755S12 11 8 11s-5 1.755-5 1.755V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z" />
                                    <path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                </svg></span>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                aria-describedby="username" name="username" value=
                                
                                "<?php
                                    if(isset($_SESSION['usernameWritenInForm'])){
                                        echo $_SESSION['usernameWritenInForm'];
                                        unset ($_SESSION['usernameWritenInForm']);
                                    }
                                    ?>"
                                 required> 
                                
                        </div>
                                <?php
                                    if(isset($_SESSION['errorWithUsername'])){
                                        echo '<div class="text-danger font-monospace">'.$_SESSION['errorWithUsername'].'</div>';
                                        unset($_SESSION['errorWithUsername']);
                                    }
                                ?>
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="email">@</span>
                            <input type="email" class="form-control" placeholder="Email" aria-label="Email"
                                aria-describedby="email" name="email" value= 
                                
                                "<?php
                                    if(isset($_SESSION['emailWritenInForm'])){
                                        echo $_SESSION['emailWritenInForm'];
                                        unset ($_SESSION['emailWritenInForm']);
                                    }
                                    ?>"
                                required>
                        </div>
                        <?php
                                    if(isset($_SESSION['errorWithEmail'])){
                                        echo '<div class="text-danger font-monospace">'.$_SESSION['errorWithEmail'].'</div>';
                                        unset($_SESSION['errorWithEmail']);
                                    }
                                ?>
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="password"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                                </svg></span>
                            <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                aria-describedby="password" name="password" value=
                                "<?php
                                    if(isset($_SESSION['passwordWritenInForm'])){
                                        echo $_SESSION['passwordWritenInForm'];
                                        unset ($_SESSION['passwordWritenInForm']);
                                    }
                                    ?>"
                                required>
                        </div>
                        <?php
                                    if(isset($_SESSION['errorWithPassword'])){
                                        echo '<div class="text-danger font-monospace">'.$_SESSION['errorWithPassword'].'</div>';
                                        unset($_SESSION['errorWithPassword']);
                                    }
                                ?>
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="password_confirm"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-clipboard-check"
                                    viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                    <path
                                        d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                    <path
                                        d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                </svg></span>
                            <input type="password" class="form-control" placeholder="Confirm password"
                                aria-label="password confirm" aria-describedby="password confirm" name="confirmPassword" vaule=
                                "<?php
                                    if(isset($_SESSION['confirmPasswordWritenInForm'])){
                                        echo $_SESSION['confirmPasswordWritenInForm'];
                                        unset ($_SESSION['confirmPasswordWritenInForm']);
                                    }
                                    ?>"
                                required>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn btn-success mt-3 btn-lg">Rejestracja</button>
                        </div>

                    </form>

                    <a class="btn btn-primary mb-4" href="log in.php" role="button">Masz już konto?</a>

                </div>

            </div>

        </main>

    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>