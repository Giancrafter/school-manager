<?php include("config.php"); include("navbar.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/uikit.min.css">
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/chart.js"></script>
    <title><?=$config_name?> | <?=$config_home?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding">
        <div class="uk-child-width-1-3@s uk-grid-medium uk-text-center uk-margin uk-dark" uk-grid="masonry: true">
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Nächste Prüfungen</h3>
                <table class="uk-table uk-table-divider uk-text-left" style="width: 1fr;">
                    <thead>
                        <tr>
                            <th>Fach</th>
                            <th>Thema</th>
                            <th>Datum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mathematik</td>
                            <td>Binomische Formeln</td>
                            <td>21.03.2021</td>
                        </tr>
                        <tr>
                            <td>Naturwissenschaften</td>
                            <td>Schroedingers Katze</td>
                            <td>01.04.2021</td>
                        </tr>
                        <tr>
                            <td>Englisch</td>
                            <td>Wörter</td>
                            <td>12.05.2021</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Notenschnitt</h3>
                <h1 style="font-size:5em; color:red;">3.95</h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Punkte</h3>
                <h1 style="font-size:5em; color:grey;">-1.5</h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Semester</h3>
                <h1>Ungenügend</h1>
                <h4 style="color:red;">Notenschnitt unter 4</h4>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Ungenügende Noten</h3>
                <h1 style="font-size:5em; color:grey;">2</h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title">Verlauf</h3>
                <canvas id="myChart" width="400" height="200"></canvas>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Binomische Formeln', 'Chemie Test 2', 'Französisch Habitat', 'Französisch mündlich', 'Englisch Stories', 'Deutsch Aufsatz'],
        datasets: [{
            label: 'Note',
            data: [1.9, 3.8, 2.8, 3.9, 4.8, 6]
        }]
    },
    lineTension: false,
});
</script>
            </div>
        </div>

      </div>
    </main>
</body>
</html>