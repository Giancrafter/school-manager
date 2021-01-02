<?php 
 include("config.php"); 
 include("db_connect.php");

 if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	die();
}

if ( isset($_SESSION["language"]) && isset( $_SESSION["class"]) ){
    header('Location: index.php');
    die();
}

//First Run Setup
if( isset($_POST["language"]) && isset ($_POST["class"])) {
    //Escape Strings
    $language = $con->real_escape_string($_POST['language']);
    $class = $con->real_escape_string($_POST['class']);
    //DB Magic
    $stmt = $con->prepare('SELECT name FROM classes WHERE name = ?');
    $stmt->bind_param('s', $class);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        if (in_array($language, $available_lang)) {
            $stmt = $con->prepare('UPDATE users SET language = ?, class = ? WHERE username = ?');
            $stmt->bind_param('sss', $language, $class, $_SESSION["username"]);
            $stmt->execute();
            $stmt->close();
            $_SESSION["class"]=$class;
            $_SESSION["language"]=$language;
        }
        }
    echo <<< MAIN
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
            <script>function err() {UIkit.modal.alert('{$lang['not-yet']}')}</script>
            <title>$config_name</title>
            <link rel="stylesheet" href="css/navbar.css">
        </head>
        <body>
        <div class="uk-padding">
            <h1>{$lang["all-set"]}</h1><hr />
            {$lang["whats-next"]}
            <form>
            <button class="uk-button uk-button-secondary" type="submit">{$lang["done"]}</button>
            </form>
            </div>  
        </body>
        </html>
    MAIN;
    die();
}


$langs = "";
foreach ($languages as $row) {
   if($_SESSION['language'] == $row[0]){$s="selected";}else{$s="";}
   $langs .= "<option value=\"{$row[0]}\" {$s}>{$row[1]}</option>";
 }

 $classes = "";
 $stmt = $con->prepare("SELECT * FROM classes");
 $stmt->execute();
 $result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
  if($_SESSION['class'] == $row["name"]){$s="selected";}else{$s="";}
  $classes .= "<option value=\"{$row['name']}\" {$s}>{$row['name']}</option>";
 }
 $stmt->close();

echo <<< WELCOME
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
        <title>$config_name</title>
        
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="manifest" href="manifest.json" />
        <!-- ios support -->
        <link rel="apple-touch-icon" href="img/logo_512.png" />
    
        <meta name="theme-color" content="#fdfdfd" />
    </head>
    <body>
        <div class="uk-padding">
                <h1>{$lang['welcome']}!</h1>
                {$lang['first-setup']}
                <hr>
                <form method="POST">
                {$lang['select-language']}
                <select class="uk-select" name="language">
                $langs
                </select>
                {$lang['select-class']}
                <select class="uk-select" name="class">
                $classes
                </select>
                <br><br>
                <button class="uk-button uk-button-secondary">{$lang['save']}</button>
                </form>
                </div>
            </div>
        </div>

    </body>
    </html>
WELCOME;

?>