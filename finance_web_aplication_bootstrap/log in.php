<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log-in site</title>

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
                        A zatem do dzieła!
                    </h1>

                    <p class="h5 my-3 fw-bolder font-monospace">Jeszcze tylko krótka formalność..</p>

                    <form action="#" class="m-4">

                        <div class="input-group mb-2">
                            <span class="input-group-text" id="email-logging">@</span>
                            <input type="email" class="form-control" placeholder="Email" aria-label="email-logging"
                                aria-describedby="email-logging" required>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="password"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                                </svg></span>
                            <input type="password" class="form-control" placeholder="Password" aria-label="Password"
                                aria-describedby="password" required>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success mt-3 btn-lg">Zaloguj</button>
                        </div>


                    </form>

                    <a class="btn btn-primary mb-4" href="registration.php" role="button">Nie masz konta?</a>

                </div>

            </div>

        </main>



    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>