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

 if(isset( $_POST["old-pw"], $_POST["new-pw"], $_POST["re-new-pw"])) {
    $data = array();
    if($_POST["new-pw"] == $_POST["re-new-pw"]) {
            if ($stmt = $con->prepare('SELECT password FROM users WHERE username = ?')) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($password_db);
                $stmt->fetch();
                if (password_verify($_POST["old-pw"], $password_db)) {
                    $stmt = $con->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $password = password_hash($_POST["new-pw"], PASSWORD_DEFAULT);
                    $stmt->bind_param("ss", $password, $_SESSION["username"]);
                    $stmt->execute();
                    $data['success'] = true;
                    $data['message'] = $lang['change-success'];
                } else {
                    $data['success'] = false;
                    $data['error']  = $lang['wrong-password'];
                }
        }
} else {
        $data['success'] = false;
        $data['error']  = $lang['register_password'];
 }
 
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
    <title><?=$config_name?> | <?=$lang['profile']?></title>
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
            <hr>
            <legend class="uk-legend"><?=$lang['change-password']?></legend><br>
            <form id="pw"> 
            <div class="uk-margin">
                <label for=""><?=$lang['old-password']?></label>
                <input type="password" class="uk-input" name="old-pw"></input>
            </div>
            <div class="uk-margin">
                <label for=""><?=$lang['new-password']?></label>
                <input type="password" class="uk-input" name="new-pw"></input>
            </div>
            <div class="uk-margin">
                <label for=""><?=$lang['repeat-new-password']?></label>
                <input type="password" class="uk-input" name="re-new-pw"></input>
            </div>
            <div id="messagebox"></div>
            <button class="uk-button uk-button-secondary"><?=$lang['save']?></button>
            </form>

 

        </fieldset>
    </div>
    </main>
<script>
$(function () {

$('#lang').on('submit', function (e) {
  e.preventDefault();
  $.ajax({
    type: 'post',
    data: $('#lang').serialize(),
  })
  .done(function(data) {
    data  = JSON.parse(data);
    if (data.success) {
      setTimeout(function() { window.location.href="index.php"; }, 1200);
    }

});
});
});
$(function () {

$('#pw').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
            type: 'post',
            data: $('#pw').serialize(),

        })
        .done(function(data) {
            console.log(data);
            data  = JSON.parse(data);
            if ( ! data.success) {
                    $('#messagebox').html('<div class="uk-alert uk-alert-danger uk-animation-fade">' + data.error + '</div>');
            } else {
            $('#pw').html('<div class="uk-alert uk-alert-success uk-animation-fade">' + data.message + '</div>');
            setTimeout(function() { window.location.href="logout.php"; }, 1200); }
            
    });
    });
    });
</script>
</body>
</html>