<?php 
include("config.php"); 
if (isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    include("db_connect.php"); 
    $users = $db->users; 
    $user = $db->$users->findOne(array('username'=> $user, 'password'=> $password));
    if(sizeof($user) == 0){
        echo('user not found');
    }

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
    <title><?=$config_name?> | <?=$config_login?></title>
</head>
<body>
    <div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
        <div class="uk-width-1-1">
            <div class="uk-container">
                <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                    <div class="uk-width-1-1@m">
                        <div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                            <h3 class="uk-card-title uk-text-center"><?=$config_name?></h3>
                            <form method="POST">
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$config_user?>" type="text" name="user">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                        <input class="uk-input uk-form-large" placeholder="<?=$config_password?>" type="password" name="password">	
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <input type="submit" class="uk-button uk-button-primary uk-button-large uk-width-1-1" value="<?=$config_login?>" name="login"></input>
                                </div>
                                <div class="uk-text-small uk-text-center">
                                    <?=$config_not_registered?> <a href="register.php"><?=$config_create_account?></a>
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