
let userRating = document.querySelector('.js-user-rating');
let isAuthenticated = userRating.dataset.isAuthenticated;
let dataPoints = JSON.parse(userRating.dataset.user);

let userRatingIncome = document.querySelector('.js-user-rating_income');
let isAuthenticatedIncome = userRatingIncome.dataset.isAuthenticatedIncome;
let dataPointsIncome = JSON.parse(userRatingIncome.dataset.user);

let options = document.getElementById("view-balance-period-of-time");

options.addEventListener("change",
    function () {

        if (options.value != "") {

            this.form.submit();
        }

    });

window.onload = function () {

    let chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Rozkład przychodów według kategorii"
        },
        subtitles: [{
            text: ""
        }],
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: dataPointsIncome

        }]
    });
    chart.render();

    let chart2 = new CanvasJS.Chart("chartContainer2", {
        animationEnabled: true,
        title: {
            text: "Rozkład wydatków według kategorii"
        },
        subtitles: [{
            text: ""
        }],
        data: [{
            type: "pie",
            yValueFormatString: "#,##0.00\"%\"",
            indexLabel: "{label} ({y})",
            dataPoints: dataPoints

        }]
    });
    chart2.render();

};