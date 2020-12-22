<?php 
include("config.php"); 
if (isset($_POST['username'])&&isset($_POST['password'])){
    //Array for the AJAX response   
    $data           = array();
    //Connect to DB
    include("db_connect.php"); 
    //Read data from POST and escape it
    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);

    if ($stmt = $con->prepare('SELECT id, password, class FROM users WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param('s', $username);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
      if ($stmt->num_rows > 0) {
          $stmt->bind_result($id, $password_db, $_SESSION['class']);
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
              $data['message'] = $lang['login_success'];
            
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
    <title><?=$config_name?> | <?=$lang['login']?></title>
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
                                <div class="uk-margin">
                                    <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1" value="<?=$lang['login']?>" name="login"></input>
                                </div>
                                <div class="uk-text-small uk-text-center">
                                    <?=$lang['not_registered']?> <a href="register.php"><?=$lang['create_account']?></a>
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