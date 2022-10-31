<?php

require_once("config.php");

$connect = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

if(mysqli_connect_errno())
{
	die("Database connection failed: ".
		mysqli_connect_error() ." (".
		mysqli_connect_errno(). ")"
		);
}

?>