<?php 
include("config.php"); 
include("db_connect.php"); 
if (isset($_POST['username'])&&isset($_POST['password'])){
    //DIE
    if ($_POST['username'] == "" || $_POST['password'] == ""){
        die();
    }
    //Array for the AJAX response   
    $data           = array();
    //Read data from POST and escape it
    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);

    if ($stmt = $con->prepare('SELECT id, password, class, language, token FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $username);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
      if ($stmt->num_rows > 0) {
          $stmt->bind_result($id, $password_db, $_SESSION['class'], $_SESSION['language'], $token);
          $stmt->fetch();
          // Account exists, now we verify the password.
          // Note: remewmber to use password_hash in your registration file to store the hashed passwords.
          if (password_verify($password, $password_db)) {
              // Verification success! User has logged in!
              // Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
              session_regenerate_id();
              $_SESSION['loggedin'] = TRUE;
              $_SESSION['username'] = $_POST['username'];
              $_SESSION['id'] = $id;
              $data['success'] = true;
              $data['message'] = $lang['login_success']."<script>localStorage.setItem('token', '{$token}');</script>";
            
          } else {
                //Wrong username or password    
                $data['success'] = false;
                $data['error']  = $lang['login_wrong'];
          }
      } else {
            //Wrong username or password
            $data['success'] = false;
            $data['error']  = $lang['login_wrong'];
    
        $stmt->close();
      }
// return all our data to an AJAX call

    }
    echo json_encode($data);
    die();
}

if (isset($_POST['token'])){
    $token = $con->real_escape_string($_POST['token']);
    if ($stmt = $con->prepare('SELECT id, username, class, language FROM users WHERE token = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $token);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
      if ($stmt->num_rows > 0) {
          $stmt->bind_result($_SESSION['id'], $_SESSION['username'], $_SESSION['class'], $_SESSION['language']);
          $stmt->fetch();
              session_regenerate_id();
              $_SESSION['loggedin'] = TRUE;
              echo "loggedin";    
      } 
    }
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
    <title><?=$config_name?> | <?=$lang['login']?></title>
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
                                        <input class="uk-input uk-form-large" placeholder="<?=$lang['user']?>" type="text" name="username">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$lang['password']?>" type="password" name="password">	
                                    </div>
                                </div>
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                <div class="uk-margin">
                                    <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1" value="<?=$lang['login']?>" name="login"></input>
                                </div>
                                <div class="uk-text-small uk-text-center">
                                    <?=$lang['not_registered']?> <a href="register.php"><?=$lang['create_account']?></a>
                                </div>
                            </form>
                        </div>
                        <div style="text-align: center; font-size:0.8em;">v.1.0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>if(localStorage.getItem('token') !== null){
        $.post( "login.php", { token: localStorage.getItem('token') })
        .done(function( data ) {
            if (data == "loggedin") {
           window.location.href="index.php";
        }});
        };

    </script>
</body>
</html>