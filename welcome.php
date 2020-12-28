<?php 
 include("config.php"); 
 include("db_connect.php");

 if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	die();
}

//First Run Setup
if( isset($_POST["language"]) && isset ($_POST["class"])) {
    //DB Magic

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
        <nav class="uk-navbar-container" uk-navbar>
            <div class="uk-navbar-left ">
                <ul class="uk-navbar-nav">
                    <a class="uk-navbar-item uk-logo " onclick="err()">$config_name</a>
                </ul>
            </div>
            <div class="uk-navbar-right">
            <ul class="uk-navbar-nav uk-visible@s">
                    <li class=" $m_a "><a onclick="err()"><span uk-icon="icon: list"></span>&nbsp;{$lang['marks']}</a></li>
                    <li class=" $m_e "><a onclick="err()"><span uk-icon="icon: file-edit"></span>&nbsp;{$lang['exams']}</a></li>
                    <li class=" $m_o "><a onclick="err()"><span uk-icon="icon: world"></span>&nbsp;{$lang['other']}</a></li>
                    <li class=" $m_p "><a onclick="err()"><span uk-icon="icon: user"></span>&nbsp;{$_SESSION['username']}</a></li>
                    <li><a href="logout.php" style="color:red;"><span uk-icon="icon: sign-out"></span>&nbsp;{$lang['logout']}</a></li>
                    </ul>
                    <a onclick="err()" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>
            </div>
            </nav>
            <div id="sidenav" uk-offcanvas="flip: true" class="uk-offcanvas">
            <div class="uk-offcanvas-bar">
            <ul class="uk-nav">
                    <li><a onclick="err()" class="uk-navbar-toggle uk-hidden@s" uk-toggle="target: #sidenav"></a></li>
                    <li>
                        <a onclick="err()" class=" $m_a ">
                        <span uk-icon="icon: list"></span>
                            &nbsp;{$lang['marks']}
                        </a>
                    </li>
                    <li>
                        <a onclick="err()" class=" $m_e ">
                            <span uk-icon="icon: file-edit"></span>
                            &nbsp; {$lang['exams']}
                        </a>
                    </li>
                    <li>
                        <a onclick="err()" class=" $m_o ">
                            <span uk-icon="icon: world"></span>
                            &nbsp;{$lang['other']}
                        </a>
                    </li>
                    <li>
                        <a onclick="err()" class=" $m_p ">
                            <span uk-icon="icon: user"></span>
                            &nbsp;{$_SESSION['username']}
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" style="color:red;">
                            <span uk-icon="icon: sign-out"></span>
                            &nbsp;{$lang['logout']}
                        </a>
                    </li>
            </ul>
            </div>
            </div>
            <div class="uk-padding">
            Welcome stuff & Navbar
            <form>
            <button class="uk-button uk-button-secondary" type="submit" name="dashboard">Next</button>
            </form>
            </div>  
        </body>
        </html>
    MAIN;
    die();
}

if ( isset( $_GET["dashboard"])) {
    echo <<< DASH
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
        </head>
        <body>
            <div class="uk-padding">
            Dashboard
            <div class="uk-child-width-1-3@s uk-grid-medium uk-text-center uk-margin uk-dark" uk-grid="masonry: true">
            <div>
            <div class="uk-card uk-card-default uk-card-body">
            <h3 class="uk-card-title">{$lang['next_exams']}</h3>
            <div class="uk-overflow-auto">
            <table class="uk-table uk-table-divider uk-table-responsive uk-text-left" style="width: 1fr;">
                <thead>
                    <tr>
                        <th>{$lang['topic']}</th>
                        <th>{$lang['date']}</th>
                    </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">{$lang['mark_average']}</h3>
                    <h1 style="font-size:5em; color:{$avg_color};">{$average}</h1>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">{$lang['points']}</h3>
                    <h1 style="font-size:5em; color:grey;">{$points}</h1>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">{$lang['semestre']}</h3>
                    <h1>Ungenügend</h1>
                    <h4 style="color:red;">Notenschnitt unter 4</h4>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">{$lang['bad_marks']}</h3>
                    <h1 style="font-size:5em; color:grey;">{$insufficient}</h1>
                </div>
            </div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <h3 class="uk-card-title">{$lang['mark_history']}</h3>
                    <canvas id="myChart"></canvas>
        <script>
        Chart.defaults.global.legend.display = false;
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [{$dia_labels}],
            datasets: [{
                label: 'Note',
                data: [{$dia_data}]
            }]
        },
        options: {
        scales:{ xAxes: [{ display: false }] },     
        }});
        </script>
                </div>
            </div>
            <form>
            <button class="uk-button uk-button-secondary" type="submit" name="exams">Next</button>
            </form>
            </div>  
        </body>
        </html>
    DASH;
    die();
}

if ( isset( $_GET["exams"])) {
    echo <<< EXAMS
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
        </head>
        <body>
            <div class="uk-padding">
            Exams
            <article class="uk-article">
            <h1 class="uk-article-title"><a class="uk-link-reset" href="">Title</a></h1>
            <p class="uk-text-lead">Subject</p>
            <p>1970-01-01</p>
            <p>
                Description
            </p>
            </article>
            <hr />
            <br />
            <button type="button" class="uk-button uk-button-secondary" style="border-radius:50px;"><span uk-icon="icon: plus"></span></button>
            <br /><br />
            <form>
            <button class="uk-button uk-button-secondary" type="submit" name="marks">Next</button>
            </form>
            </div>  
        </body>
        </html>
    EXAMS;
    die();
}

if ( isset( $_GET["marks"])) {
    echo <<< MARKS
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
        </head>
        <body>
            <div class="uk-padding">
            Marks
            <ul uk-accordion>
            <li>
            <a class="uk-accordion-title"><span class="uk-align-left">{$row['name']}</span><span class="uk-align-right">{$avg_final}</span></a>
            <div class="uk-accordion-content">
            <div class="uk-flex uk-flex-between uk-flex-wrap uk-flex-wrap-around">
                <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@m">
                <h3>Noten</h3>
                <table class="uk-table uk-table-divider">
                    <thead>
                        <tr>
                            <th>Test</th>
                            <th>Note</th>
                            <th>Schnitt</th>
                            <th>Rang</th>
                        </tr>
                    </thead>
                    <tbody>
                       $mark_table
                    </tbody>
                </table>
                </div>
                <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-5@m">
                <h3>Note hinzufügen</h3>
                <form method="POST">
                <div class="uk-margin">
                <label for="">Prüfung auswählen:</label>
                <select class="uk-select" required name="exam">
                    $add_select
                </select>
                </div>
                <div class="uk-margin">
                    <label for="">Note:</label>
                    <input class="uk-input" type="number"  step="0.01" min="1" max="6" placeholder="1.00" name="mark" required>
                </div>
                <input type="hidden" name="subject" value="{$row['name']}"></input>
                <button type="submit" class="uk-button uk-button-default uk-button-large">Save</button>
                </form>
                </div>
                <div class="uk-card uk-card-default uk-margin-small uk-card-body uk-width-1-1 uk-width-1-2@s uk-width-1-4@m">
                <h3>Verlauf</h3>
                    <canvas width="400" height="200" id="{$row['name']}"></canvas>
                    <script>
                    Chart.defaults.global.legend.display = false;
                    var ctx = document.getElementById('{$row['name']}').getContext('2d');
                    var {$row['name']} = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: $dia_labels,
                            datasets: [{
                                label: 'Note',
                                data: $dia_data
                            }]
                        },
                        options: {
                        scales:{ xAxes: [{ display: false }], 
                        yAxes : [{ 
                            ticks: {
                                max: 6,
                                min: 1
                            },
                        }] },   
                    }});
                    </script>
                </div>
            </div>
            </div>
            </li>
            </ul>
            <form>
            <button class="uk-button uk-button-secondary" type="submit" name="profile">Next</button>
            </form>
            </div>  
        </body>
        </html>
    MARKS;
    die();
}

if ( isset( $_GET["profile"])) {
    echo <<< PROFILE
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
        </head>
        <body>
            <div class="uk-padding">
            Marks
            <form>
            <button class="uk-button uk-button-secondary">Done</button>
            </form>
            </div>  
        </body>
        </html>
    PROFILE;
    die();
}


$langs = "";
foreach ($languages as $row) {
   if($_SESSION['language'] == $row[0]){$s="selected";}else{$s="";}
   $langs .= "<option value=\"{$row[0]}\" {$s}>{$row[1]}</option>";
 }

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
                <option>BMI 20-24 A</option>
                <option>BMI 20-24 B</option>
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