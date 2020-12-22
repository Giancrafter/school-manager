 <?php 
 include("config.php"); 
 include("navbar.php");
 $langs = "";
 foreach ($languages as $row) {
    $langs .= "<option name=\"{$row[0]}\">{$row[1]}</option>";
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
            <label for="">Select language:</label>
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