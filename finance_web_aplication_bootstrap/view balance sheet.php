<?php

session_start();
if(!isset($_SESSION["isLoggedIn"])) {
    header("Location: index.php");
    exit();
}

$dataPoints = array();

     foreach($_SESSION['groupResults'] as $expenseGroup){
       array_push($dataPoints, array("label"=>$expenseGroup['expense_category'], 
       "y"=>round($expenseGroup['expense_sum_of_categories']/$_SESSION["expenseSum"][0]*100, 2)));
    }
	// array("label"=>"Chrome", "y"=>64.02),
	// array("label"=>"Firefox", "y"=>12.55),
	// array("label"=>"IE", "y"=>8.47),
	// array("label"=>"Safari", "y"=>6.08),
	// array("label"=>"Edge", "y"=>4.29),
	// array("label"=>"Others", "y"=>4.59)

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View balance sheet</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">

        

</head>

<body>
    

    <div class="container-fluid bg-image  d-flex justify-content-center"
    style="background-image: url(img/background_stars2.jpg); height: 100%;">

        <main>

            <div class="row justify-content-center">

                <!-- col-12 col-sm-10 -->
                <div id="main-container" class="col-10 col-lg-12 bg-white rounded-5 shadow-lg border bg-image"
                    style="background-image: url(img/main_menu_graphic_site.png); height: 100%;">

                    <div>
                        <h1 class="h1 my-4 fw-bolder font-monospace text-center text-dark bg-white p-2 rounded-4">
                            Wszystko
                            co
                            potrzebne do
                            monitorowania
                            budżetu <a class="nav-link d-inline-block px-2" target="_blank"
                                href="https://github.com/MaurycyE"><svg xmlns="http://www.w3.org/2000/svg" width="20"
                                    height="20" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                </svg></a></h1>
                    </div>

                    

                    <div class="d-flex float-sm-start col-8 col-sm-4 px-4 mb-3">
                        <ul class="nav nav-tabs flex-column  bg-light rounded-4">
                            <li class="nav-item p-2">
                                <a class="nav-link text-dark" href="add income.php"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-clipboard-data" viewBox="0 0 16 16">
                                        <path
                                            d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z" />
                                        <path
                                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                        <path
                                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                    </svg> Dodaj przychód</a>
                            </li>
                            <li class="nav-item p-2">
                                <a class="nav-link text-dark" href="add expense.php"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-graph-down" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M0 0h1v15h15v1H0V0Zm14.817 11.887a.5.5 0 0 0 .07-.704l-4.5-5.5a.5.5 0 0 0-.74-.037L7.06 8.233 3.404 3.206a.5.5 0 0 0-.808.588l4 5.5a.5.5 0 0 0 .758.06l2.609-2.61 4.15 5.073a.5.5 0 0 0 .704.07Z" />
                                    </svg> Dodaj wydatek</a>
                            </li>
                            <li class="nav-item p-2">
                                <a class="nav-link text-light bg-dark active" aria-current="page"
                                    href="view balance sheet.php"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                        height="16" fill="currentColor" class="bi bi-pie-chart" viewBox="0 0 16 16">
                                        <path
                                            d="M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793V1.018zm1 0V7.5h6.482A7.001 7.001 0 0 0 8.5 1.018zM14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
                                    </svg> Przeglądaj bilans</a>
                            </li>
                            <li class="nav-item p-2">
                                <a class="nav-link text-dark" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-gear"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
                                        <path
                                            d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" />
                                    </svg> Ustawienia</a>
                            </li>
                            <li class="nav-item p-2">
                                <a class="nav-link text-dark" href="logout_mechanism.php"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right"
                                        viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                        <path fill-rule="evenodd"
                                            d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                    </svg> Wyloguj</a>
                            </li>
                        </ul>
                    </div>
                    
                    
                    
                    
                    <div class="col-12 col-sm-8 float-sm-end bg-white p-4 rounded-4 mb-2">

                        <div>
                            <h2 class="font-monospace">Przeglądaj bilans</h2>
                        </div>

                        <form action="send balance data.php" method="post">
                            <div class="col-6 col-lg-4">
                                <label for="view-balance-period-of-time" class="form-label"></label>
                                <select class="form-select" aria-label="view balance sheet" id="view-balance-period-of-time"
                                    name="periodOfTime" required>
                                    <option value="Bieżący miesiąc" <?php echo $_SESSION['selectedCurrentMonthOption']; ?> >Bieżący miesiąc</option>
                                    <option value="Poprzedni miesiąc" <?php echo $_SESSION['selectedPreviousMonthOption']; ?> >Poprzedni miesiąc</option>                                  
                                    <option value="Bieżący rok" <?php echo $_SESSION['selectedCurrentYearOption']; ?> >Bieżący rok</option>
                                    <option value="" data-bs-toggle="modal"
                                        data-bs-target="#view-balance-modal" <?php echo $_SESSION['selectedNotStandardOption']; ?>>
                                        niestandardowe</option>
                                </select>
                               
                            </div>

                        </form>

                        <!-- Modal -->
                        <div class="modal fade" id="view-balance-modal" tabindex="-1"
                            aria-labelledby="view-balance-modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="view-balance-modalLabel">Wybierz zakres
                                            dat:
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="send balance data notstandard.php" method="post">

                                                <div class="mb-3">
                                                    <label for="view-balance-date-from" class="form-label">Od:</label>
                                                    <input type="date" class="form-control" id="view-balance-date-from"
                                                        aria-describedby="first date to view balance sheet" min="2001-01-01"
                                                        name="firstNotStandardDate" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="view-balance-date-to" class="form-label">Do:</label>
                                                    <input type="date" class="form-control" id="view-balance-date-to"
                                                        aria-describedby="second date to view balance sheet"
                                                        min="2001-01-01" name="secondNotStandardDate" required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                    <path
                                                    d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                                </svg></button>
                                                </div>
                                        
                                        </form>
                                    </div>

                                </div>
                            </div>
                           
                        </div>
                        <!-- Modal -->

                        <h3 class=" mt-3 fw-bolder font-monospace">Przychody</h3>
                        <div class="table-responsive">
                        <table class="table table-sm mt-1">
                            <thead>
                                <tr><th>Kwota</th><th>Kategoria</th><th>Data</th><th>Komentarz</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($_SESSION["incomeResult"])){
                                        foreach($_SESSION["incomeResult"] as $userIncomeData) {
                                            echo "<tr><td> {$userIncomeData['income_amout']} </td><td> {$userIncomeData['income_category']}</td> 
                                            <td> {$userIncomeData['income_date']} </td><td> {$userIncomeData['income_comment']}</td></tr>";
                                        }
                                        echo "<tr><td class='text-success font-monospace'> {$_SESSION['incomeSum'][0]} </td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        </div>

                        <h3 class=" mt-3 fw-bolder font-monospace">Wydatki</h3>
                        <div class="table-responsive">
                        <table class="table table-sm mt-1">
                            <thead>
                                <tr><th>Kwota</th><th>Kategoria</th><th>Metoda Płatności</th><th>Data</th><th>Komentarz</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($_SESSION["expenseResult"])){
                                        foreach($_SESSION["expenseResult"] as $userExpenseData) {
                                            echo "<tr><td> {$userExpenseData['expense_amout']} </td><td> {$userExpenseData['expense_category']}</td>
                                            <td> {$userExpenseData['expense_payment_method']} </td><td> {$userExpenseData['expense_date']} </td>
                                            <td> {$userExpenseData['expense_description']} </td></tr>";
                                        }
                                        echo "<tr><td class='text-danger font-monospace'> {$_SESSION['expenseSum'][0]} </td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        </div>
                        <h3 class=" mt-3 fw-bolder font-monospace">Bilans</h3>
                        <?php
                            $textColor;
                            if($_SESSION['incomeSum'][0]-$_SESSION['expenseSum'][0]>0)
                                    $textColor="text-success";
                            else
                                    $textColor="text-danger";
                                    
                            echo '<span class="font-monospace h4">'.$_SESSION['incomeSum'][0].' - </span>'
                            .'<span class="font-monospace h4">'.$_SESSION['expenseSum'][0].' = </span> <span class="'.$textColor.' font-monospace h4">'
                            .$_SESSION['incomeSum'][0]-$_SESSION['expenseSum'][0].'</span>';
                            
                        ?>
                        <button id="pie_chart" class="btn font-monospace btn-success mx-4 float-sm-end mt-2" data-bs-toggle="modal"
                                        data-bs-target="#chart_modal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line mx-2" viewBox="0 0 16 16">
                                        <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
                                        </svg>Zobacz wykres</button>
                        <!-- <div id="chartContainer" style="height: 370px; width: 100%;"></div> -->
                        <!-- WYKRES -->
                        <!-- <div class="col-10 col-sm-8 d-flex row" id="chartContainer"></div> -->
                        

                        <!-- MODAL - CHART -->
                        <div class="modal fade" id="chart_modal" tabindex="-1"
                            aria-labelledby="chart_modalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content" id="chartContainer">
                                    <div class="modal-header">
                                        <!-- <h1 class="modal-title fs-5" id="chart_modalLabel">Wybierz zakres
                                            dat:
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button> -->
                                            
                                    </div>
                                    <div class="modal-body">
                                        <!-- <div class="col-10 col-sm-8 d-flex row" id="chartContainer"></div> -->
                                    </div>


                                </div>
                            </div>
                           
                        </div>

                    </div>

                </div>

            </div>
        </main>

    </div>

    <script>
        
        let options = document.getElementById("view-balance-period-of-time");
        
            options.addEventListener("change", 
            function(){
                if(options.value!="") {
                    //alert(options.value);
                    this.form.submit();
                }
                
            });
        
    </script>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    
    <script>
        //window.onload = function() {}
        document.getElementById("pie_chart").addEventListener("click", function(){
        
            let chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Rozkład wydatków według kategorii"
                },
                subtitles: [{
                    text:"<?php echo $_SESSION["selectedPeriodOfTime"] ?>"
                }],
                data: [{
                    type: "pie",
                    yValueFormatString: "#,##0.00\"%\"",
                    indexLabel: "{label} ({y})",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();
        
        });
        </script>
</body>

</html>