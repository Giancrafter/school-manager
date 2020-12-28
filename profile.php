 <?php 
 include("config.php"); 
 include("navbar.php");
 include("db_connect.php");


 $langs = "";
 foreach ($languages as $row) {
    if($_SESSION['language'] == $row[0]){$s="selected";}else{$s="";}
    $langs .= "<option value=\"{$row[0]}\" {$s}>{$row[1]}</option>";
  }
 if (isset($_POST['language'])) {
    $data = array();
    $new_language = $con->real_escape_string($_POST['language']);
    if (in_array($new_language, $available_lang)) {
        $stmt = $con->prepare("UPDATE users SET language = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_language, $_SESSION['username']);
        $stmt->execute();
        $data['success'] = true;
        $data['message'] = $lang['language_change_success'];
    } else {
        $data['success'] = false;
        $data['error']  = $lang['language_not_found'];
    }
    $_SESSION['language'] = $new_language;
    echo json_encode($data);
    die();
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
    <script src="js/script.js"></script>
    <title><?=$config_name?> | <?=$lang['profile']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
    <div class="uk-margin">
        <fieldset class="uk-fieldset">

            <legend class="uk-legend">Account</legend>

            <div class="uk-margin">
                <b><?=$lang['user']?></b>:  <?=$_SESSION['username']?></b><br />
                <b><?=$lang['class']?></b>:  <?=$_SESSION['class']?></b><br /><br />
                <i style="font-size: 0.75em;"><?=$lang['account-change']?></i>
            </div>
            
            <hr>
            <form id="lang"> 
            <div class="uk-margin">
            <label for=""><?=$lang['select-language']?></label>
                <select name="language" class="uk-select">
                <?=$langs?>
                </select>
            </div>
            <button class="uk-button uk-button-secondary"><?=$lang['save']?></button>
            </form>
 

        </fieldset>
    </div>
    </main>

</body>
</html>