<?php
if (file_get_contents('config.php') != "") {
    die();
}
if( isset($_POST["name"], $_POST["user"], $_POST["password"], $_POST["db"], $_POST["host"], $_POST["port"] ) ) {

$con = mysqli_connect($_POST["host"], $_POST["user"], $_POST["password"], $_POST["db"], $_POST["port"]);
if ( mysqli_connect_errno() ) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$con->query("CREATE TABLE IF NOT EXISTS `classes` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8_german2_ci NOT NULL,
    `semesters` int NOT NULL,
    `start` varchar(6) COLLATE utf8_german2_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci");
$con->query("CREATE TABLE IF NOT EXISTS `exams` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) COLLATE utf8_german2_ci NOT NULL,
    `subject` varchar(20) COLLATE utf8_german2_ci NOT NULL,
    `description` text COLLATE utf8_german2_ci NOT NULL,
    `class` varchar(30) COLLATE utf8_german2_ci NOT NULL,
    `date` varchar(20) COLLATE utf8_german2_ci NOT NULL,
    `user_from` varchar(30) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci");
$con->query("CREATE TABLE IF NOT EXISTS `marks` (
    `id` int NOT NULL AUTO_INCREMENT,
    `exam` varchar(50) COLLATE utf8_german2_ci NOT NULL,
    `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
    `class` varchar(30) COLLATE utf8_german2_ci NOT NULL,
    `subject` varchar(30) COLLATE utf8_german2_ci NOT NULL,
    `mark` decimal(3,2) NOT NULL,
    `weight` decimal(2,1) NOT NULL DEFAULT '1.0',
    `semester` varchar(6) COLLATE utf8_german2_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci");
$con->query("CREATE TABLE IF NOT EXISTS `subjects` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(30) COLLATE utf8_german2_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci");
$con->query("CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` char(30) COLLATE utf8_german2_ci NOT NULL,
    `password` text COLLATE utf8_german2_ci NOT NULL,
    `class` varchar(30) CHARACTER SET utf8 COLLATE utf8_german2_ci DEFAULT NULL,
    `language` varchar(2) CHARACTER SET utf8 COLLATE utf8_german2_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci");
  
$file = fopen("config.php", "w");
$content = <<< EOT
<?php
session_start();
\$config_name = "{$_POST["name"]}";

#MySQL
\$config_mysql_user = "{$_POST["user"]}";
\$config_mysql_password = "{$_POST["password"]}";
\$config_mysql_host = "{$_POST["host"]}";
\$config_mysql_database = "{$_POST["db"]}";
\$config_mysql_port = "{$_POST["port"]}";

#Language Strings
include("language-maps.php");

#PHP Session
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
setcookie(session_name(),session_id(),time()+2628000);

#reCaptcha
\$recaptcha_site_key = "{$_POST["recaptcha_key"]}";
\$recaptcha_secret_key = "{$_POST["recaptcha_secret"]}";
?>
EOT;

fwrite($file, $content);
fclose($file);
header("Location: login.php");
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
        <title>Setup</title>
    </head>
        <body>
        <div class="uk-padding">
            <h1>Setup</h1>
            <hr>
            <form method="POST">
                <label for="">Site Name</label>
                <input type="text" name="name" value="SchoolManager"></input><br><br>

                <label for="">MySQL Database</label>
                <input type="text" name="db" value=""></input><br><br>

                <label for="">MySQL Username</label>
                <input type="text" name="user" value=""></input><br><br>

                <label for="">MySQL Password</label>
                <input type="text" name="password" value=""></input><br><br>

                <label for="">MySQL Host</label>
                <input type="text" name="host" value="localhost"></input><br><br>

                <label for="">MySQL Port</label>
                <input type="number" name="port" value="3306"></input><br><br>

                <label for="">reCaptcha Key</label>
                <input type="text" name="recaptcha_key" value=""></input><br><br>

                <label for="">reCaptcha Secret</label>
                <input type="text" name="recaptcha_secret" value=""></input><br><br>

                <input type="submit" value="Save"></input>
            </form>
        </div>
        </body>
</html>