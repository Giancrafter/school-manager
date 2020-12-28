<?php
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	die();
}

if (!isset( $_SESSION["language"] ) || !isset( $_SESSION["class"] )) {
    header('Location: welcome.php');
    die();
}


$uri = $_SERVER['REQUEST_URI'];
$username = $_SESSION['username'];
if(stristr($uri, "marks.php")){
    $m_a="active";
} else if(stristr($uri, "exams.php")) {
    $m_e="active";
} else if(stristr($uri, "other.php")) {
    $m_o="active";
} else if(stristr($uri, "profile.php")) {
    $m_p="active";
}

$navbar = <<<EOT
<nav class="uk-navbar-container" uk-navbar>

<div class="uk-navbar-left ">

    <ul class="uk-navbar-nav">
        <a class="uk-navbar-item uk-logo " href="/">$config_name</a>
    </ul>
</div>
<div class="uk-navbar-right">
 <ul class="uk-navbar-nav uk-visible@s">
        <li class=" $m_a "><a href="marks.php"><span uk-icon="icon: list"></span>&nbsp;{$lang['marks']}</a></li>
        <li class=" $m_e "><a href="exams.php"><span uk-icon="icon: file-edit"></span>&nbsp;{$lang['exams']}</a></li>
        <li class=" $m_o "><a href="other.php"><span uk-icon="icon: world"></span>&nbsp;{$lang['other']}</a></li>
        <li class=" $m_p "><a href="profile.php"><span uk-icon="icon: user"></span>&nbsp;$username</a></li>
        <li><a href="logout.php" style="color:red;"><span uk-icon="icon: sign-out"></span>&nbsp;{$lang['logout']}</a></li>
        </ul>
        <a href="#" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>
</div>

</nav>
<div id="sidenav" uk-offcanvas="flip: true" class="uk-offcanvas">
<div class="uk-offcanvas-bar">
<ul class="uk-nav">
        <li><a href="#" class="uk-navbar-toggle uk-hidden@s" uk-toggle="target: #sidenav"></a></li>
        <li>
            <a href="marks.php" class=" $m_a ">
            <span uk-icon="icon: list"></span>
                &nbsp;{$lang['marks']}
            </a>
        </li>

        <li>
            <a href="exams.php" class=" $m_e ">
                <span uk-icon="icon: file-edit"></span>
                &nbsp; {$lang['exams']}
            </a>
        </li>

        <li>
            <a href="other.php" class=" $m_o ">
                <span uk-icon="icon: world"></span>
                &nbsp;{$lang['other']}
            </a>
        </li>

        <li>
            <a href="profile.php" class=" $m_p ">
                <span uk-icon="icon: user"></span>
                &nbsp;$username
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
EOT;
?>