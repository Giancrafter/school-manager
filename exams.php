<?php 
include("config.php"); 
include("db_connect.php");
include("navbar.php");
include("scripts/Parsedown.php");
//Code for removing exams
if ( isset($_POST["remove"])){
    $id = $con->real_escape_string($_POST['id']);
    $stmt=$con->prepare("SELECT * FROM exams WHERE id = ? AND user_from = ?");
    $stmt->bind_param("ss", $id, $_SESSION["username"]);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows>0){
        $stmt=$con->prepare("DELETE FROM exams WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    }
    $stmt->close();
}
//Code for adding new exams
if (isset($_POST['name'])&&isset($_POST['date'])&&isset($_POST['subject'])&&isset($_POST['description'])) {
    $e_name = $con->real_escape_string($_POST['name']);
    $e_date = $con->real_escape_string($_POST['date']);
    $e_subject = $con->real_escape_string($_POST['subject']);
    $e_description = $con->real_escape_string($_POST['description']);
    $stmt = $con->prepare("INSERT INTO exams (name, subject, description, class, date, user_from) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $e_name, $e_subject, $e_description, $_SESSION['class'], $e_date, $_SESSION['username']);
    $stmt->execute();
    if (isset($_GET["addnew"])){
    header("Location: marks.php");
    }
}
//Fetch exams
if ($stmt = $con->prepare('SELECT id, name, subject, description, date, user_from FROM exams WHERE class = ? ORDER BY date DESC')) {
    $stmt->bind_param('s', $_SESSION['class']);
    $stmt->execute();
    $result = $stmt->get_result();
    $exams = "";
while($row = $result->fetch_assoc()) {
    //Do markdown magic
    $Parsedown = new Parsedown();
    $row['description'] = $Parsedown->text(str_replace("\\r","\r",str_replace("\\n","\n",htmlspecialchars ($row['description']))));
    $row['name'] = htmlspecialchars($row['name']);
    $row['subject'] = htmlspecialchars($row['subject']);
    $row['date'] = htmlspecialchars($row['date']);
    if ( $_SESSION['language'] == "de" ) {
        $date = new DateTime($row['date']);
        $row['date'] = $date->format('d.m.Y');
    }
    if ($row['user_from'] == $_SESSION["username"]){
        $remove = <<<EOT
            <form method="POST" style="display: inline;">
                <input type="hidden" name="id" value="{$row['id']}">
                </input>
                <button class="" style="color:white; background-color: black; padding:5px; border-radius: 5px;" type="submit" name="remove" uk-icon="icon: close" uk-tooltip="title: {$lang['remove']}; pos: top-left"></button>
            </form>
        EOT;
    } else {$remove="";}
    $exams.= <<<EOT
        <article class="uk-article">
        <h1 class="uk-article-title"><a class="uk-link-reset" href="">{$row['name']}$remove</a></h1>
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
$stmt = $con->prepare('SELECT * FROM subjects');
$stmt->execute();
$result = $stmt->get_result();
$subjects="";
foreach ($result as $row) {
    $subjects .= "<option value=\"{$row['name']}\" {$s}>{$row['name']}</option>";
  }

}

?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/uikit.min.css">
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
    <script src="js/jquery.min.js"></script>
    
    <title><?=$config_name?> | <?=$lang['exams']?></title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="manifest" href="manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="img/logo_512.png" />

    <meta name="theme-color" content="#fdfdfd" />
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
        <form method="post">
        <h2 class="uk-modal-title"><?=$lang['add-exam']?></h2>
        <div class="uk-margin">
            <input class="uk-input" type="text" placeholder="<?=$lang['exam'] ?>" name="name">
        </div>
        <div class="uk-margin">
            <select class="uk-select" name="subject">
                <?=$subjects?>
            </select>
        </div>
        <div class="uk-margin">
            <input class="uk-input" type="date" name="date">
        </div>
        <div class="uk-margin">
            <textarea class="uk-textarea" rows="6" placeholder="<?=$lang['description']?>" name="description"></textarea>
            <span style="font-size: 0.75em;"><?=$lang['markdown-notice']?></span>
            
        </div>
        <button class="uk-button uk-button-secondary" type="submit"><?=$lang['save']?></button>
        <button class="uk-modal-close uk-button uk-button-secondary" type="button"><?=$lang['close']?></button>
        </form>
    </div>
</div>
<button uk-toggle="target: #add-exam" type="button" class="uk-button uk-button-secondary" style="position: fixed;  bottom:40px; right:40px; border-radius:50px;"><span uk-icon="icon: plus"></span></button>
</body>
</html>
<?php 
if (isset($_GET["addnew"])){
    echo ("<script>UIkit.modal(\"#add-exam\").show();</script>");
}
?>