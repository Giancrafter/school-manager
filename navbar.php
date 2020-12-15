<?php
$navbar = <<<EOT
<nav class="uk-navbar-container" uk-navbar>

<div class="uk-navbar-left ">

    <ul class="uk-navbar-nav ">
        <a class="uk-navbar-item uk-logo " href="/">$config_name</a>
    </ul>
</div>
<div class="uk-navbar-right">
 <ul class="uk-navbar-nav uk-visible@s">
        <li><a href="marks.php">$config_marks</a></li>
        <li><a href="exams.php">$config_exams</a></li>
        <li><a href="other.php">$config_other</a></li>
        <li><a href="exams.php">$config_exams</a></li>
        <li><a href="other.php">$config_other</a></li>
        </ul>
        <a href="#" class="uk-navbar-toggle uk-hidden@s" uk-navbar-toggle-icon uk-toggle="target: #sidenav"></a>
</div>

</nav>
<div id="sidenav" uk-offcanvas="flip: true" class="uk-offcanvas">
<div class="uk-offcanvas-bar">
<ul class="uk-nav">
        <li><a href="#" class="uk-navbar-toggle uk-hidden@s" uk-toggle="target: #sidenav"></a></li>
        <li>
            <a href="marks.php">
            <span uk-icon="icon: list"></span>
                $config_marks
            </a>
        </li>

        <li>
            <a href="exams.php">
                <span uk-icon="icon: file-edit"></span>
                $config_exams
            </a>
        </li>

        <li>
            <a href="other.php">
                <span uk-icon="icon: world"></span>
                $config_other
            </a>
        </li>

        <li>
            <a href="profile.php">
                <span uk-icon="icon: user"></span>
                $config_profile
            </a>
        </li>
        <li>
            <a href="logout.php" style="color:red;">
                <span uk-icon="icon: sign-out"></span>
                $config_logout
            </a>
        </li>
</ul>
</div>
</div>
EOT;
?>