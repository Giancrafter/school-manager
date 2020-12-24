<?php 
include("config.php"); 
include("navbar.php");
include("db_connect.php");
//SELECT all subjects
$stmt = $con->prepare("SELECT name FROM subjects");
$stmt->execute();
$result = $stmt->get_result();
$marks="";
//Loop through all Subjects
while($row = $result->fetch_assoc()) {
    //Set Select and Mark variables
    $add_select = "";
    $mark_table = "";
    //Get a list of all exams
    $stmt = $con->prepare("SELECT * FROM exams WHERE subject = ?");
    $stmt->bind_param("s", $row["name"]);
    $stmt->execute();
    $exam_list = $stmt->get_result();
    //Loop through exams
    while($exam_single = $exam_list->fetch_assoc()) {
        //SELECT all exams that marks exist for
        $stmt = $con->prepare("SELECT * FROM marks WHERE exam = ? AND subject = ? AND username = ?");
        $stmt->bind_param("sss", $exam_single["name"], $row["name"], $_SESSION["username"]);
        $stmt->execute();
        $result_exams = $stmt->store_result();
        //Check if mark of the exam has already been set
        if ($stmt->num_rows > 0) {
            $mark_table .= <<< EOT
                <tr> 
                    <td>?</td>
                    <td>?</td>
                    <td>?</td>
                    <td>??/??</td>
                </tr>
            EOT;
        } else {
            $add_select .= "<option value=\"{$exam_single["name"]}\">{$exam_single["name"]}</option>";
        }
        
        }

        $stmt = $con->prepare("SELECT * FROM marks WHERE subject = ? AND username = ?");
    $stmt->bind_param("ss", $row["name"], $_SESSION["username"]);
    $stmt->execute();
    $marks_list = $stmt->get_result();    
    $marks.= <<< EOT
    <li>
    <a class="uk-accordion-title"><span class="uk-align-left">{$row['name']}</span><span class="uk-align-right">1.0</span></a>
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
               $mark_table
            </tbody>
        </table>
        </div>
        <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-5@m">
        <h3>Note hinzufügen</h3>
        <form>
        <div class="uk-margin">
        <label for="">Prüfung auswählen:</label>
        <select class="uk-select">
            $add_select
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
            <canvas width="400" height="200" id="{$row['name']}"></canvas>
            <script>
            Chart.defaults.global.legend.display = false;
            var ctx = document.getElementById('{$row['name']}').getContext('2d');
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