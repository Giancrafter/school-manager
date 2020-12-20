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
    <title><?=$config_name?> | <?=$lang['exams']?></title>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <?=$navbar?>
    <main class="uk-padding uk-animation-fade" >
    <div class="uk-margin">
    <article class="uk-article">

    <h1 class="uk-article-title"><a class="uk-link-reset" href="">Exam 1</a></h1>

    <p class="uk-text-lead">Maths</p>
    <p>01.01.2000</p>


    <p>
        <ul>
            <li>thing 1</li>
            <li>thing 2</li>
            <li>thing 3</li>
        </ul>
    </p>

    </article>
    <hr />
</div>
</main>
</body>
</html>