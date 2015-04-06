<?php

if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
   
	//Connect to DB 
	include("db_connect.php");

	//Checking input for funky stuff
	$email = mysqli_real_escape_string($mydb, $_GET['email']);
	$hash = mysqli_real_escape_string($mydb, $_GET['hash']);

	//Check for matching email and hash
	$result = mysqli_query($mydb, "SELECT Email, Hash FROM tutee WHERE Email='".$email."' AND Hash='".$hash."'");
	//If match do this
	if($result){
		//Update verified of account to 1 from 0
		mysqli_query($mydb, "UPDATE tutee SET Verified='1' WHERE Email='".$email."' AND Hash='".$hash."'");
		echo("Account verified!");
	}
	//No match do this
	else{
		echo("Failed to verify account.");
	}
}else{
	echo(json_encode("Incorrect URL. Failed to verify account."));
}

?>
