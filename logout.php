<?php
session_start();
session_destroy();
?>
<script>localStorage.removeItem('token'); window.location.href="login.php";</script>