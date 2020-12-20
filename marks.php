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
    <title><?=$config_name?> | <?=$lang['marks']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
    <div class="uk-margin uk-dark">
    <ul uk-accordion>
    <li>
        <a class="uk-accordion-title"><span class="uk-align-left">Mathematik</span><span class="uk-align-right">1.0</span></a>
        <div class="uk-accordion-content">
        <div class="uk-flex uk-flex-between uk-flex-wrap uk-flex-wrap-around">
            <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@m">
            <h3>Noten</h3>
            <table class="uk-table uk-table-divider">
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Note</th>
                        <th>Schnitt</th>
                        <th>Rang</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name1</td>
                        <td>1.0</td>
                        <td>4.0</td>
                        <td>12/18</td>
                    </tr>
                    <tr>
                        <td>Name1</td>
                        <td>1.0</td>
                        <td>4.0</td>
                        <td>12/18</td>
                    </tr>
                    <tr>
                        <td>Name1</td>
                        <td>1.0</td>
                        <td>4.0</td>
                        <td>12/18</td>
                    </tr>
                </tbody>
            </table>

            </div>
            <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-5@m">
            <h3>Note hinzufügen</h3>
            <form>
            <div class="uk-margin">
            <label for="">Prüfung auswählen:</label>
             <select class="uk-select">
                 <option>Option 01</option>
                 <option>Option 02</option>
             </select>
            </div>
            <div class="uk-margin">
                <label for="">Note:</label>
                <input class="uk-input" type="number"  step="0.01" min="1" max="6" placeholder="1.00">
            </div>
            <button type="submit" class="uk-button uk-button-default uk-button-large">Save</button>
            </form>
            </div>
            <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-4@m">
            <h3>Verlauf</h3>
                <canvas width="400" height="200" id="myChart"></canvas>
                <script>
                Chart.defaults.global.legend.display = false;
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
                    options: {
                    scales:{ xAxes: [{ display: false }] },     
                }});
                </script>
            </div>

        </div>
        
        </div>
    </li>
    <hr>
</ul>
</div>
</main>
</body>
</html>