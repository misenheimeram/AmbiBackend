<?php

$response = array();

if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['email']) && !empty($_POST['email']) &&
	isset($_POST['major']) && !empty($_POST['major']) && isset($_POST['bio']) && !empty($_POST['bio'])){
	//connect to db
	include("db_connect.php");

	//Making sure the input doesn't do anything weird
	$name = mysqli_real_escape_string($mydb, $_POST['name']);
	$email = mysqli_real_escape_string($mydb, $_POST['email']);
	$major = mysqli_real_escape_string($mydb, $_POST['major']);
	$bio = mysqli_real_escape_string($mydb, $_POST['bio']);

	
	$result = mysqli_query($mydb, "UPDATE tutee SET Name='".$name."', Major='".$major."', Bio='".$bio."' WHERE Email='".$email."'");

	if($result){
		$response["success"] = 1;
		$response["message"] = "Profile Updated!";
		echo json_encode($response);
	}
	//Failed to update
	else{
		$response["success"] = 0;
		$response["message"] = mysqli_error($mydb);
		echo json_encode($response);
	}
}
//Not all information set
else{
	$response["success"] = 0;
	$response["message"] = "Please fill out all required field(s)!";
	echo json_encode($response);
}
?>
