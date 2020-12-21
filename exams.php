<?php 
include("config.php"); 
include("db_connect.php");
include("navbar.php");
include("scripts/Parsedown.php");
if ($stmt = $con->prepare('SELECT id, name, subject, description, date FROM exams WHERE class = ? ORDER BY id DESC')) {
    $stmt->bind_param('s', $_SESSION['class']);
    $stmt->execute();
    $result = $stmt->get_result();
    $exams = "";
while($row = $result->fetch_assoc()) {
    $Parsedown = new Parsedown();
    $row['description'] = $Parsedown->text(htmlspecialchars ($row['description']));
    $exams.= <<<EOT
        <article class="uk-article">
        <h1 class="uk-article-title"><a class="uk-link-reset" href="">{$row['name']}</a></h1>
        <p class="uk-text-lead">{$row['subject']}</p>
        <p>{$row['date']}</p>
        <p>
            {$row['description']}
        </p>
        </article>
        <hr />
        <br />
    EOT;
}
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
    <title><?=$config_name?> | <?=$lang['exams']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
    <div class="uk-margin">
    <?=$exams?>
</div>
</main>
<div id="add-exam" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Add exam</h2>
        <button class="uk-button uk-button-secondary" type="button">Save</button>
        <button class="uk-modal-close uk-button uk-button-secondary" type="button">Close</button>
    </div>
</div>
<button uk-toggle="target: #add-exam" type="button" class="uk-button uk-button-secondary" style="position: fixed;  bottom:40px; right:40px; border-radius:50px;"><span uk-icon="icon: plus"></span></button>
</body>
</html>