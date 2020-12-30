<?php 
include("config.php"); 
include("navbar.php");
include("db_connect.php");

//Function for getting Rank
function getRank($g_array, $g_value){
arsort($g_array);
$position=0;
while ($g_value <= $g_array[$position]) {
    $position++;
}
return $position+1;
}
//Add Mark
if ( isset( $_POST["exam"]) && isset( $_POST["mark"])) {
    $post_exam = $con->real_escape_string($_POST['exam']);
    $post_mark = $con->real_escape_string($_POST['mark']);
    $post_subject = $con->real_escape_string($_POST['subject']);
    //Default weight
    $weight=1.0;
    $sql_add = $con->prepare("SELECT * FROM exams WHERE name = ? AND subject = ?");
    $sql_add->bind_param("ss", $post_exam, $post_subject);
    $sql_add->execute();
    $sql_add->store_result();
    if ($sql_add->num_rows > 0) {
        if($post_mark>=1.0&&$post_mark<=6.0) {
            $sql_add_final = $con->prepare("INSERT IGNORE INTO marks (exam, username, class, subject, mark, weight) VALUES (?, ?, ?, ?, ?, ?)");
            $sql_add_final->bind_param("ssssss", $post_exam, $_SESSION["username"], $_SESSION["class"], $post_subject, $post_mark, $weight);
            $sql_add_final->execute();
            header("Location: marks.php");
            die();
        } else {
            echo "NEIN2";
        }
    } else {
        echo "NEIN";
    }
}
//SELECT all subjects
$sql_subject_select = $con->prepare("SELECT name FROM subjects");
$sql_subject_select->execute();
$result = $sql_subject_select->get_result();
$sql_subject_select->close();
$marks="";
//Loop through all Subjects


while($row = $result->fetch_assoc()) {
    //Set Select and Mark variables
    $add_select = "";
    $mark_table = "";

    $avg_count=0;
    $avg=0;
    //Get a list of all exams
    
    $sql_exam_select = $con->prepare("SELECT name FROM exams WHERE subject = ? AND class = ?");
    $sql_exam_select->bind_param("ss", $row["name"], $_SESSION["class"]);
    $sql_exam_select->execute();
    $exam_list = $sql_exam_select->get_result();
    $sql_exam_select->close();
    //Diagram Variables
    $dia_labels = "[";
    $dia_data = "[";
    //Loop through exams
    while($exam_single = $exam_list->fetch_assoc()) {
        //SELECT all exams that marks exist for
        $sql_mark_exam = $con->prepare("SELECT * FROM marks WHERE exam = ? AND subject = ? AND username = ?");
        $sql_mark_exam->bind_param("sss", $exam_single["name"], $row["name"], $_SESSION["username"]);
        $sql_mark_exam->execute();
        $result_exams = $sql_mark_exam->get_result()->fetch_assoc();
        //Check if mark of the exam has already been set
        if (count($result_exams) > 0) {
            $avg_count++;
            $avg+=$result_exams["mark"];
            $class_count=1;
            $class_marks=$result_exams["mark"];
            $ranking = array();
            $s_subject = $row["name"];
            $sql_class = $con->prepare("SELECT exam, mark FROM marks WHERE class = ? AND exam = ? AND subject  = ? AND username <> ?");
            $sql_class->bind_param("ssss", $_SESSION["class"], $exam_single["name"], $s_subject, $_SESSION["username"]);
            $sql_class->execute();
            $sql_class_result=$sql_class->get_result();
            
            while($class_element = $sql_class_result->fetch_assoc()){
                $class_count++;
                $class_marks+=$class_element["mark"];
                $ranking[] = $class_element["mark"];

            }
            $class_avg = round($class_marks/$class_count, 2);
            $pos_in_class=getRank($ranking, $result_exams["mark"]);
            $sql_class->close();
            $mark_table .= <<< EOT
                <tr> 
                    <td>{$result_exams["exam"]}</td>
                    <td>{$result_exams["mark"]}</td>
                    <td>$class_avg</td>
                    <td>$pos_in_class/{$class_count}</td>
                </tr>
            EOT;
            $dia_labels.="'{$result_exams["exam"]}', ";
            $dia_data.="'{$result_exams["mark"]}', ";
        } else {
            
            $add_select .= "<option value=\"{$exam_single["name"]}\">{$exam_single["name"]}</option>";
        }
        }
        //Diagram end
        $dia_labels.="]";
        $dia_data.="]";

    $avg_final=($avg_count>0) ? (round($avg/$avg_count, 2)) : ("?");
    $mark_table=($mark_table!="") ? ($mark_table) : ("<td colspan=\"4\"><i>No marks set yet.</i></td>");
    $marks.= <<< EOT
    <li>
    <a class="uk-accordion-title"><span class="uk-align-left">{$row['name']}</span><span class="uk-align-right">{$avg_final}</span></a>
    <div class="uk-accordion-content">
    <div class="uk-flex uk-flex-between uk-flex-wrap uk-flex-wrap-around">
        <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@m">
        <h3>Noten</h3>
        <div class="uk-overflow-auto">
        <table class="uk-table uk-table-divider">
            <thead>
                <tr>
                    <th>{$lang['exam']}</th>
                    <th>{$lang['mark']}</th>
                    <th>{$lang['class-avg']}</th>
                    <th>{$lang['rank']}</th>
                </tr>
            </thead>
            <tbody>
               $mark_table
            </tbody>
        </table>
        </div>
        </div>
        <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-5@m">
        <h3>{$lang['add-mark']}</h3>
        <form method="POST">
        <div class="uk-margin">
        <label for="">Prüfung auswählen:</label>
        <select class="uk-select" required name="exam">
            $add_select
        </select>
        </div>
        <div class="uk-margin">
            <label for="">{$lang['mark']}:</label>
            <input class="uk-input" type="number"  step="0.01" min="1" max="6" placeholder="1.00" name="mark" required>
        </div>
        <input type="hidden" name="subject" value="{$row['name']}"></input>
        <button type="submit" class="uk-button uk-button-default uk-button-large">{$lang['save']}</button>
        </form>
        </div>
        <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-4@m">
        <h3>{$lang['mark_history']}</h3>
            <canvas width="400" height="200" id="{$row['name']}"></canvas>
            <script>
            Chart.defaults.global.legend.display = false;
            var ctx = document.getElementById('{$row['name']}').getContext('2d');
            var {$row['name']} = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: $dia_labels,
                    datasets: [{
                        label: 'Note',
                        data: $dia_data
                    }]
                },
                options: {
                scales:{ xAxes: [{ display: false }], 
                yAxes : [{ 
                    ticks: {
                        max: 6,
                        min: 1
                    },
                }] },   
            }});
            </script>
        </div>
    </div>
    </div>
    </li>
    EOT;

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
    <title><?=$config_name?> | <?=$lang['marks']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
    <div class="uk-margin uk-dark">
    <ul uk-accordion>
        <?=$marks?>
    <hr>
</ul>
</div>
</main>
</body>
</html>