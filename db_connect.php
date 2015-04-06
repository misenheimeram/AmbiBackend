<?php
	//Grab the values for the server
	include("db_config.php");
	//Create a connection to the server called $mydb
	$mydb = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

?>
