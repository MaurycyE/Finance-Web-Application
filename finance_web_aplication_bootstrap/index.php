<?php
session_start();

unset($_SESSION['error']);

if((isset($_SESSION['isLoggedIn']))&&($_SESSION['isLoggedIn']==true)) {
    header('Location: main menu.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">
</head>

<body>

    <div class="container-fluid bg-image  d-flex justify-content-center align-items-center"
        style="background-image: url(img/background_stars2.jpg); height: 100vh;">

        <main>

            <div class="row justify-content-center text-center">

                <div class="col-10 col-sm-6 col-lg-4 bg-white rounded-5 shadow-lg border">

                    <h1 class="h1 my-3 fw-bolder font-monospace">Zaplanuj swój budżet zawsze i wszędzie!</h1>

                    <div class="col-sm-8 mx-auto">
                        <img src="img/main_site_graphic2.png" alt="main_site_graphic" class="img-fluid">
                    </div>

                    <a class="btn btn-dark my-4 mx-4 p-2 btn-lg" href="registration.php" role="button">Rejestracja</a>
                    <a class="btn btn-dark mx-4 p-2 btn-lg" href="log in.php" role="button">Logowanie</a>

                </div>

            </div>

        </main>



    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>