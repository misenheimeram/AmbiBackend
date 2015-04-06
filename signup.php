<?php

//This is what we will use to store data for our response
$response = array();

//Checking for valid email
if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-.]+.edu$/", $_POST['email'])){
   	// Return Error - Invalid Email
	$response["success"] = 0;
	$response["message"] = "Invalid email address";
   	// Return Error - Invalid Email
	echo json_encode($response);
}

//Checking that it has email, name, and password
elseif(isset($_POST['email']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['password']) && !empty($_POST['password'])){

	include("db_connect.php");

	//Setting email, name, and password to local variables
	$email = mysqli_real_escape_string($mydb, $_POST['email']);
	$name = mysqli_real_escape_string($mydb, $_POST['name']);
	$password = mysqli_real_escape_string($mydb, $_POST['password']);
	$gender = NULL;
	
	//If gender was set, including gender
	if(isset($_GET['gender'])){
		$gender = mysqli_real_escape_string($mydb, $_POST['gender']);
	}

	//Using PHPass to hash and salt the password. Only the hash needs to be stored in the DB.
	//PHPass's CheckPassword will be used to check password on login.
	//TODO: Password needs to be checked for length < 72, fail otherwise. Hash needs to be checked for len > 20, fail otherise.
	require("PasswordHash.php");
	$hasher = new PasswordHash(8, false);
	$hash = $hasher->HashPassword($password);
	

	//Connect to database

	//Inserting the row and recording the result
	$result = mysqli_query($mydb,"INSERT INTO tutee(Email, Hash, Name, Gender) VALUES('$email','$hash','$name','$gender')");

	//If the insert statement was successful
	if($result){
		//Grabbing the pear mail file
		require_once "Mail.php";

		//Grabbing $from, $host, $port, $username, and $password
		include("email_config.php");

		//Building verification email
		$to = $email;
		$subject = "Ambi Account Verification";
		$body = '
		Welcome to Tute! 
		Before you get started we just want to make sure that you are using a valid email address. 
		Please click the link below to verify your email!

		http://68.119.36.37/tute/verify.php?email='.$email.'&hash='.$hash.'

		';
		$headers = array ('From' => $from, 'To' => $to,'Subject' => $subject);
		
		//Sending verification email
		$smtp = Mail::factory('smtp',
		  	array ('host' => $host,
		    		'port' => $port,
				'auth' => true,
		    		'username' => $username,
		    		'password' => $password));

		$mail = $smtp->send($to, $headers, $body);
		
		if(PEAR::isError($mail)){
			echo($mail->getMessage());
		}


		$response["success"] = 1;
		$response["message"] = "User successfully added!";
		echo json_encode($response);
	}
	//If not successful do this
	else{
		$response["success"] = 0;
		$response["message"] = "Error, user not added.";
		echo json_encode($response);
	}
	//All done with database so close the connection
	mysqli_close($mydb);
}
//Email, Password, or name not included
else{
	$response["success"] = 0;
	$response["message"] = "Required field(s) missing.";
	echo json_encode($response);
}
?>
