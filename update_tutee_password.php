<?php

$response = array();

if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) &&
	isset($_POST['new_password']) && !empty($_POST['new_password'])){
	
	//Connect to DB
	include("db_connect.php");

	//Making sure input isn't something funky
	$password = mysqli_real_escape_string($mydb, $_POST['password']);
	$new_password = mysqli_real_escape_string($mydb, $_POST['new_password']);
	$email = mysqli_real_escape_string($mydb, $_POST['email']);

	//Grab hash of actual password
	$result = mysqli_query($mydb, "SELECT Hash FROM tutee WHERE Email='".$email."'");
	
	if($result){

		$row = mysqli_fetch_array($result);
		$hash = $row["Hash"];

		require("PasswordHash.php");
		$hasher = new PasswordHash(8, false);
		$check = $hasher->CheckPassword($password, $hash);

		if($check){
			$new_hash = $hasher->HashPassword($new_password);

			$result = mysqli_query($mydb, "UPDATE tutee SET Hash = '$new_hash' WHERE Email = '$email'");

			if($result){
				$response['success'] = 1;
				$response['message'] = "Password updated!";
				echo json_encode($response);
			}
			
			else{
				$response['success'] = 0;
				$response['message'] = "Failed to updated password.";
				echo json_encode($response);
			}
		}

		else{
			$response['success'] = 0;
			$response['message'] = "Incorrect password";
			echo json_encode($response);
		}

	}

	else{
		$response['success'] = 0;
		$response['message'] = "Error: Something went wrong!";
		echo json_encode($response);
	}

}

else{
	$response['success'] = 0;
	$response['message'] = "Please fill out all required field(s)!";
	echo json_encode($response);
}
