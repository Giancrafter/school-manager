<?php
session_start();
$config_name = "Komischer Name hier einsetzen";

#MySQL
$config_mysql_user = "root";
$config_mysql_password = "enterpasswordhere";
$config_mysql_host = "localhost";
$config_mysql_database = "schoolmanager";
$config_mysql_port = "3306";

#Language Strings
$config_user = "Username";
$config_password = "Password";
$config_repeat_password = "Repeat Password";
$config_login = "Login";
$config_register = "Register";
$config_not_registered = "Not registered?";
$config_already_registered = "Already have an account?";
$config_create_account = "Create an account";

$config_home = "Home";
$config_marks = "Marks";
$config_exams = "Exams";
$config_other = "Other";
$config_profile = "Profile";
$config_logout = "Logout";

$config_login_success = "Logged in successfully, redirecting...";
$config_login_wrong = "Wrong username or password.";
$config_register_success = "Registration successful. You are now logged in.";
$config_register_exists = "Username already exists.";
$config_register_password = "Passwords don't match.";
