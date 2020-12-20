<?php include("config.php"); include("navbar.php") ?>
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
    <form>
        <fieldset class="uk-fieldset">

            <legend class="uk-legend">Account</legend>

            <div class="uk-margin">
                <label for="">Username: </label>
                <input class="uk-input" type="text" placeholder="Username">
            </div>

            <div class="uk-margin">
            <label for="">Select class:</label>
                <select class="uk-select">
                    <option>Class A</option>
                    <option>Class B</option>
                </select>
            </div>
            <div class="uk-margin">
            <label for="">Select language:</label>
                <select class="uk-select">
                    <option>English</option>
                    <option>Deutsch</option>
                </select>
            </div>
 

        </fieldset>
    </form>
    </div>
    </main>

</body>
</html>