<?php 
include("config.php"); 
if (isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['password_re'])){
        
    //Array for the AJAX response   
    $data           = array();
    //Connect to db
    include("db_connect.php"); 
    //Read Data frim $_POST and escape it
    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);
    $password_re = $con->real_escape_string($_POST['password_re']);

    //DIE
    if ($_POST['username'] == "" || $_POST['password'] == ""){
        die();
    }

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = $recaptcha_secret_key;
    $recaptcha_response = $_POST['recaptcha_response'];

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->score >= 0.5) {

    if(preg_match("/^[a-zA-Z0-9_]+$/",$username)){
    if ($password!=$password_re) {
        //Passwords don't match
        $data['success'] = false;
        $data['error']  = $lang['register_password'];
    } else {
    if ($stmt = $con->prepare('SELECT id, password FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc)
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        // Store the result so we can check if the account exists in the database.
        if ($stmt->num_rows > 0) {
        // Username already exists
        $data['success'] = false;
        $data['error']  = $lang['register_exists'];
        } else {
        // Username doesnt exists, insert new account
        if ($stmt = $con->prepare('INSERT INTO users (username, password, token) VALUES (?, ?, ?)')) {
            // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
            $password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(40));
            $stmt->bind_param('sss', $username, $password, $token);
            $stmt->execute();
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $conn->insert_id;
            //User created
            $data['success'] = true;
            $data['message'] = $lang['register_success']."<script>localStorage.setItem('token', '{$token}');</script>";
}}}}
}  else {
    $data['success'] = false;
    $data['error']  = $lang['username-error'];
}

} else {
    $data['success'] = false;
    $data['error']  = $lang['captcha-error'];
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
    <script src="js/script.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?=$recaptcha_site_key?>"></script>
    <title><?=$config_name?> | <?=$lang['register']?></title>
    <link rel="manifest" href="manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="img/logo_512.png" />

    <meta name="theme-color" content="#fdfdfd" />
</head>
<body>
    <div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center"><?=$config_name?></h3>
                            <form>
                                <div id="messagebox"></div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$lang['user']?>" type="text" required="required" name="username">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$lang['password']?>" type="password" required="required" name="password">	
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$lang['repeat_password']?>" type="password" required="required" name="password_re">	
                                    </div>
                                </div>
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                <div class="uk-margin">
                                    <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1" value="<?=$lang['register']?>" name="register"></input>
                                </div>
                                <div class="uk-text-small uk-text-center">
                                    <?=$config_already_registered?> <a href="login.php"><?=$lang['already_registered']?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>