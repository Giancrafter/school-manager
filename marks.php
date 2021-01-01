<?php 
include("config.php"); 
include("navbar.php");
include("db_connect.php");

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

//Function for getting Rank
function getRank($g_array, $g_value){
arsort($g_array);
$position=0;
while ($g_value <= $g_array[$position]) {
    $position++;
}
return $position+1;
}

//Generate row for a subject
function generateRow($subject) {
    global $lang;
    global $con;
    global $semesters;
    global $class_start;
    global $currentSemester;
    $request_id = uniqid();
    //Set Select and Mark variables
    $add_select = "";
    $mark_table = "";
    $avg_count=0;
    $avg=0;
    //Get a list of all exams
    $sql_exam_select = $con->prepare("SELECT name FROM exams WHERE subject = ? AND class = ?");
    $sql_exam_select->bind_param("ss", $subject, $_SESSION["class"]);
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
        $sql_mark_exam->bind_param("sss", $exam_single["name"], $subject, $_SESSION["username"]);
        $sql_mark_exam->execute();
        $result_exams = $sql_mark_exam->get_result()->fetch_assoc();
        $sql_mark_exam->close();
        //Check if mark of the exam has already been set
        if (count($result_exams) > 0) {
            if ($result_exams["semester"] == $currentSemester){
            $avg_count+=$result_exams["weight"];
            $avg+=$result_exams["mark"]*$result_exams["weight"];}
            $class_count=1;
            $class_marks=$result_exams["mark"];
            $ranking = array();
            $s_subject = $subject;
            $sql_class = $con->prepare("SELECT id, exam, mark, weight FROM marks WHERE class = ? AND exam = ? AND subject  = ? AND username <> ?");
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
            
            //Semester Select
            $sme = $class_start;
            $semester_select = "";
            for ($q = 1; $q <= $semesters; $q++){
                if($sme==$currentSemester) {$selected="selected";} else {$selected="";}
                $semester_select .= "<option value=\"{$sme}\" $selected>{$sme}</option>";
                $sme = countUp($sme);
            }

            $mark_table .= <<< EOT
                <tr> 
                    <td>{$result_exams["exam"]}</td>
                    <td>{$result_exams["mark"]}</td>
                    <td>$class_avg</td>
                    <td>$pos_in_class/{$class_count}</td>
                    <td>{$result_exams["semester"]}</td>
                    <td>{$result_exams["weight"]}</td>
                    <td>            
                        <form id="{$result_exams["id"]}" style="display: inline;">
                        <input type="hidden" name="id" value="{$result_exams["id"]}"><input type="hidden" name="remove"></input><input type="hidden" name="subject" value="$subject"></input>
                        </input>
                        <button class="" style="color:white; background-color: black; padding:5px; border-radius: 5px;" type="submit" uk-icon="icon: close" uk-tooltip="title: {$lang['remove']}; pos: top-left"></button>
                        </form>
                        <script>
                        $(function () {
                            $('#{$result_exams["id"]}').on('submit', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    type: "POST",
                                    url: "marks.php",
                                    data: $("#{$result_exams["id"]}").serialize(),
                                    success: function(html){ 
                                            $('#r_$subject').html(html);
                                    }
                            });
                        }); });
                        </script>
                        <div id="modal_{$result_exams["id"]}_$request_id" uk-modal>
                            <div class="uk-modal-dialog uk-modal-body">
                                <form id="edit_{$result_exams["id"]}_$request_id">
                                <h2 class="uk-modal-title">{$lang['edit-exam']}</h2>
                                <div class="uk-margin">
                                    <label>{$lang['exam']}:</label>
                                    <input class="uk-input" type="text" readonly value="{$result_exams["exam"]}"></input>
                                </div>
                                <div class="uk-margin">
                                    <label>{$lang['mark']}:</label>
                                    <input class="uk-input" type="number" step="0.01" min="1" max="6" placeholder="1.00" name="mark" value="{$result_exams["mark"]}" required></input>
                                </div>
                                <div class="uk-margin">
                                    <label>{$lang['weight']}:</label>
                                    <input class="uk-input" type="number" step="0.1" min="0" max="10" placeholder="1.0" name="weight" value="{$result_exams["weight"]}" required></input>
                                </div>
                                <div class="uk-margin">
                                    <label>{$lang['semester']}:</label>
                                    <select class="uk-select" name="semester">
                                    $semester_select
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="{$result_exams["id"]}"><input type="hidden" name="edit"></input><input type="hidden" name="subject" value="$subject"></input>
                                <button style="margin-bottom: 20px;" type="submit" class="uk-button uk-button-secondary" onclick="UIkit.modal(modal_{$result_exams["id"]}_$request_id).hide();">{$lang['save']}</button>
                                <button style="margin-bottom: 20px;" class="uk-modal-close uk-button uk-button-secondary" type="button">{$lang['close']}</button>
                                </form>
                            </div>
                        </div>
                        <script>
                        $(function () {
                            $('#edit_{$result_exams["id"]}_$request_id').on('submit', function (e) {
                                e.preventDefault();
                                $.ajax({
                                    type: "POST",
                                    url: "marks.php",
                                    data: $("#edit_{$result_exams["id"]}_$request_id").serialize(),
                                    success: function(html){ 
                                            $('#r_$subject').html(html);
                                            
                                    }
                            });
                        }); 
                        });
                        </script>
                        <button style="color:white; background-color: black; padding:5px; border-radius: 5px;" uk-icon="icon: pencil" onclick="UIkit.modal(modal_{$result_exams["id"]}_$request_id).show();"  uk-tooltip="title: {$lang['edit']}; pos: top-left"></button>
                       
                    </td>
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
    $row = <<< EOT
    <a class="uk-accordion-title"><span class="uk-align-left">{$subject}</span><span class="uk-align-right">{$avg_final}</span></a>
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
                    <th>{$lang['semester']}</th>
                    <th>{$lang['weight']}</th>
                    <th>{$lang['actions']}</th>
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
        <script>
        $(function () {
            $('#$subject').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "marks.php",
                    data: $("#$subject").serialize(),
                    success: function(html){ 
                            $('#r_$subject').html(html);
                    }
            });
        }); });
        </script>
        <form id="$subject">
        <div class="uk-margin">
        <label for="">{$lang['select-exam']}:</label>
        <select class="uk-select" required name="exam">
            $add_select
        </select>
        <a style="font-size:0.8em;" href="exams.php?addnew">{$lang['add-exam']}</a><br><br>
        <label for="">{$lang['select-semester']}:</label>
        <select class="uk-select" required name="semester">
            $semester_select
        </select>
        </div>
        <div class="uk-margin">
            <label for="">{$lang['mark']}:</label>
            <input class="uk-input" type="number" step="0.01" min="1" max="6" placeholder="1.00" name="mark" required></input>
        </div>
        <div class="uk-margin">
            <label>{$lang['weight']}:</label>
            <input class="uk-input" type="number" step="0.1" min="0" max="10" placeholder="1.0" name="weight" value="{$result_exams["weight"]}" required></input>
        </div>
        <input type="hidden" name="subject" value="{$subject}"></input>
        <button type="submit" class="uk-button uk-button-default uk-button-large">{$lang['save']}</button>
        </form>
        </div>
        <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-4@m">
        <h3>{$lang['mark_history']}</h3>
            <canvas width="400" height="200" id="c_{$subject}"></canvas>
            <script>
            Chart.defaults.global.legend.display = false;
            var ctx = document.getElementById('c_{$subject}').getContext('2d');
            var {$subject} = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: $dia_labels,
                    datasets: [{
                        label: 'Note',
                        data: $dia_data,
                        trendlineLinear: {
                            style: "rgb(0, 0, 0, 0.4)",
                            lineStyle: "dotted",
                            width: 1
                        },
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
            </script>
        </div>
    </div>
    </div>
    EOT;
    return $row;
   
   }

//Add Mark
if ( isset( $_POST["exam"], $_POST["mark"], $_POST["subject"], $_POST["semester"], $_POST["weight"])) {
    $post_exam = $con->real_escape_string($_POST['exam']);
    $post_mark = $con->real_escape_string($_POST['mark']);
    $post_subject = $con->real_escape_string($_POST['subject']);
    $post_weight = $con->real_escape_string($_POST['weight']);
    $post_semester = $con->real_escape_string($_POST['semester']);

    $sql_add = $con->prepare("SELECT * FROM exams WHERE name = ? AND subject = ?");
    $sql_add->bind_param("ss", $post_exam, $post_subject);
    $sql_add->execute();
    $sql_add->store_result();
    if ($sql_add->num_rows > 0) {
        if($post_mark>=1.0&&$post_mark<=6.0) {
            $sql_add_final = $con->prepare("INSERT IGNORE INTO marks (exam, username, class, subject, mark, weight, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql_add_final->bind_param("sssssss", $post_exam, $_SESSION["username"], $_SESSION["class"], $post_subject, $post_mark, $post_weight, $post_semester);
            $sql_add_final->execute();
        } else {
        
        }
    } else {
    }
    echo generateRow($post_subject);
    die();
}
//Remove Mark
if ( isset( $_POST["remove"], $_POST["id"], $_POST["subject"])) {
    $post_id = $con->real_escape_string($_POST['id']);
    $post_subject = $con->real_escape_string($_POST['subject']);
    $sql_delete = $con->prepare("DELETE FROM marks WHERE id = ? AND username = ?");
    $sql_delete->bind_param("ss", $post_id, $_SESSION["username"]);
    $sql_delete->execute();
    echo generateRow($post_subject);
    die();
}

//Edit Mark
if ( isset( $_POST["edit"], $_POST["id"], $_POST["mark"], $_POST["weight"], $_POST["semester"])) {
    $post_id = $con->real_escape_string($_POST['id']);
    $post_mark = $con->real_escape_string($_POST['mark']);
    $post_weight = $con->real_escape_string($_POST['weight']);
    $post_semester = $con->real_escape_string($_POST['semester']);
    $post_subject = $con->real_escape_string($_POST['subject']);

    $sql_delete = $con->prepare("UPDATE marks SET mark = ?, weight = ?, semester = ? WHERE id = ? AND username = ?");
    $sql_delete->bind_param("sssss", $post_mark, $post_weight, $post_semester, $post_id, $_SESSION["username"]);
    $sql_delete->execute();
    echo generateRow($post_subject);
    die();
}

//SELECT all subjects
$sql_subject_select = $con->prepare("SELECT name FROM subjects");
$sql_subject_select->execute();
$result = $sql_subject_select->get_result();
$sql_subject_select->close();
$marks="";
//Loop through all Subjects


while($row = $result->fetch_assoc()) {
    $marks .= "<li id=\"r_{$row["name"]}\">".generateRow($row["name"])."</li>";
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
</ul>
</div>
</main>
</body>
</html>