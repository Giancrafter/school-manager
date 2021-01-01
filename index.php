<?php 
include("config.php"); 
include("db_connect.php");
include("navbar.php");
if ($stmt = $con->prepare('SELECT id, name, subject, date FROM exams WHERE class = ? ORDER BY date DESC LIMIT 3')) {
    $stmt->bind_param('s', $_SESSION['class']);
    $stmt->execute();
    $result = $stmt->get_result();
    $exams = "";
    $stmt->close();
while($row = $result->fetch_assoc()) {
    if ( $_SESSION['language'] == "de" ) {
        $date = new DateTime($row['date']);
        $row['date'] = $date->format('d.m.Y');
    }
    $exams.= <<<EOT
        <tr>
        <td><b>{$row['subject']}</b><br>
        {$row['name']}</td>
        <td>{$row['date']}</td>
    </tr>
    EOT;
}
}

//Get Semester related things
$semester_q = $con->prepare("SELECT semesters, start FROM classes WHERE name = ?");
$semester_q->bind_param("s", $_SESSION["class"]);
$semester_q->execute();
$semester_q->bind_result($semesters, $class_start);
$semester_q->fetch();
$semester_q->close();

//Function for counting the formated Semester up
function countUp($formatted){
    $parts = explode("/", $formatted);
    if ($parts[1] == 2) {
        $parts[1] = 1;
        $parts[0]++;
    } else {
        $parts[1]++;
    }
    return $parts[0]."/".$parts[1];
}

//get Semester
if (date('n')==1){
    $currentSemester = (date('Y')-1)."/";
} else {
    $currentSemester = date('Y')."/";
}
if ((date('n')>1)&&(date('n')<8)){
    $currentSemester.="1";
} else {
    $currentSemester.="2";
}

//Get subjects
$average_total=0;
$average_count=0;
$points=0;
$insufficient=0;
$dia_labels = "";
$dia_data = "";


$stmt = $con->prepare("SELECT * FROM subjects");
$stmt->execute();
$subjects = $stmt->get_result();
$stmt->close();
//Fetch subjects
while($subject = $subjects->fetch_assoc()){
$s_avg_count = 0;
$s_avg = 0;
$stmt = $con->prepare("SELECT * FROM marks WHERE username = ? AND semester = ? AND subject = ?");
$stmt->bind_param("sss", $_SESSION['username'], $currentSemester, $subject["name"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
//Fetch examss for subject and current semester
while($row = $result->fetch_assoc()) {

$s_avg_count+=$row["weight"];
$s_avg+=$row["mark"]*$row["weight"];

//end exams
}


if ($s_avg_count!= 0){
$average_count++;
$s_avg = (round($s_avg/$s_avg_count*2, 0))/2;
$average_total+=$s_avg;
//Points
$points += $s_avg -4;
//Insufficient Marks
if($s_avg <4.0){$insufficient++;}
}

//End subjects
}

if ($average_count==0) {
    $average="?";
} else {
$average=round($average_total/$average_count,2);
$avg_color = ($average<4.0) ? "red" : (($average>=5.0) ? "green" : "grey");

$semester = $lang['okay'];
$reasons="";
if ($average<4.0){
    $semester = $lang['insufficient'];  
    $reasons.=$lang['err-avg'].'<br>';
}
if ($insufficient>2){
    $semester = $lang['insufficient'];  
    $reasons.=$lang['err-mark'].'<br>';
}
if ($points<-2){
    $semester = $lang['insufficient'];  
    $reasons.=$lang['err-point'].'<br>';
}
if($semester == $lang['insufficient']){
    $err_color="red";
} else {
    $err_color="green";
}
}

$stmt = $con->prepare("SELECT * FROM marks WHERE username = ? AND semester = ?");
$stmt->bind_param("ss", $_SESSION['username'], $currentSemester);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
//Fetch examss for subject and current semester
while($row = $result->fetch_assoc()) {
//Diagram stuff
$dia_labels.="'{$row["exam"]}', ";
$dia_data.="'{$row["mark"]}', ";

}

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
                <h3 class="uk-card-title"><?=$lang['semester']?></h3>
                <h1><?=$semester?></h1>
                <h4 style="color:<?=$err_color?>;"><?=$reasons?></h4>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <h3 class="uk-card-title"><?=$lang['bad_marks']?></h3>
                <h1 style="font-size:5em; color:grey;"><?=$insufficient?></h1>
            </div>
        </div>
        <div>
            <div class="uk-card uk-card-default uk-card-body ">
                <h3 class="uk-card-title"><?=$lang['mark_history']?></h3>
                <canvas id="myChart" style="margin-right: 5px;"></canvas>
<script>
Chart.defaults.global.legend.display = false;
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?=$dia_labels?>],
        datasets: [{
            label: 'Note',
            trendlineLinear: {
                style: "rgb(0, 0, 0, 0.4)",
                lineStyle: "dotted",
                width: 1
            },
            data: [<?=$dia_data?>],

        }]
    },
    options: {
    scales:{ xAxes: [{ display: false,  }], 
    yAxes : [{ 
                    ticks: {
                        max: 6,
                        min: 1
                    },
                }] },   
    layout: {padding: { right: 15}},
    responsive: true,
    tooltips: {
      backgroundColor: 'rgba(0, 0, 0, 1)',
      intersect: false,
      displayColors: false,
    },
}});
myChart.options.scales.xAxes[0].display = (document.getElementById('myChart').clientHeight>=200);
myChart.update();
$(window).on('resize', function(){
myChart.options.scales.xAxes[0].display = (document.getElementById('myChart').clientHeight>=200);
myChart.update();
});
</script>
            </div>
        </div>

      </div>
    </main>
</body>
</html>