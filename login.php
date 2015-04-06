<?php

$response = array();

if(isset($_POST['email']) && isset($_POST['password'])){
	//Connect to db
	include("db_connect.php");

	//Store data in local variables for easy access
	$email = mysqli_real_escape_string($mydb, $_POST['email']);
	$password = mysqli_real_escape_string($mydb, $_POST['password']);

	//Get the hash value of the matching email
	$result = mysqli_query($mydb, "SELECT Email, Hash, Verified FROM tutee WHERE Email='".$email."'");
	
	//If we got a matching email
	if($result){
		//Grab the matching hash and store it in a local variable
		$row = mysqli_fetch_array($result);
		$hash = $row["Hash"];
		
		//Create an instance of the PHPass hasher to check the password against the hash
		require("PasswordHash.php");
		$hasher = new PasswordHash(8,false);
		$check = $hasher->CheckPassword($password, $hash);
		
		//If there's a match log in
		if($check){
			$response["success"] = 1;
			$response["message"] = "Logged in!";
			$response["verified"] = $row["Verified"];
		}
		//Otherwise something went wrong
		else{
			$response["success"] = 0;
			$response["message"] = "Incorrect email or password";
		}
		echo json_encode($response);
	}
}else{
	//If something
	$response["success"] = 0;
	$response["message"] = "Error reading email or password";
	echo json_encode($response);
}

?>
