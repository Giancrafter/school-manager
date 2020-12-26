<?php 
include("config.php"); 
include("db_connect.php");
include("navbar.php");
if ($stmt = $con->prepare('SELECT id, name, subject, date FROM exams WHERE class = ? ORDER BY id DESC')) {
    $stmt->bind_param('s', $_SESSION['class']);
    $stmt->execute();
    $result = $stmt->get_result();
    $exams = "";
    $stmt->close();
while($row = $result->fetch_assoc()) {
    $exams.= <<<EOT
        <tr>
        <td><b>{$row['subject']}</b><br>
        {$row['name']}</td>
        <td>{$row['date']}</td>
    </tr>
    EOT;
}
}
$stmt = $con->prepare("SELECT * FROM marks WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$average_total=0;
$average_count=0;
$points=0;
$insufficient=0;

while($row = $result->fetch_assoc()) {
//Points
$points += round(($row["mark"]-4)*2)/2;

//Insufficient Marks
if($row["mark"]<4.0){$insufficient++;}

//Average
$average_count++;
$average_total+=$row["mark"];
}
$average=round($average_total/$average_count,2);
$avg_color = ($average<4.0) ? "red" : "grey";
?>
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
    <title><?=$config_name?> | <?=$lang['home']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
        <div class="uk-child-width-1-3@s uk-grid-medium uk-text-center uk-margin uk-dark" uk-grid="masonry: true">
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['next_exams']?></h3>
                <div class="uk-overflow-auto">
                <table class="uk-table uk-table-divider uk-table-responsive uk-text-left" style="width: 1fr;">
                    <thead>
                        <tr>
                            <th><?=$lang['topic']?></th>
                            <th><?=$lang['date']?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$exams?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['mark_average']?></h3>
                <h1 style="font-size:5em; color:<?=$avg_color?>;"><?=$average?></h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['points']?></h3>
                <h1 style="font-size:5em; color:grey;"><?=$points?></h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['semestre']?></h3>
                <h1>Ungenügend</h1>
                <h4 style="color:red;">Notenschnitt unter 4</h4>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['bad_marks']?></h3>
                <h1 style="font-size:5em; color:grey;"><?=$insufficient?></h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['mark_history']?></h3>
                <canvas id="myChart"></canvas>
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
    </main>
</body>
</html>