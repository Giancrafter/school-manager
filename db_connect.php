<?php
$con = mysqli_connect($config_mysql_host, $config_mysql_user, $config_mysql_password, $config_mysql_database, $config_mysql_port);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>