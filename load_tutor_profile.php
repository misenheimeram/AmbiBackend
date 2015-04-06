<?php

$response = array();

if(isset($_POST['email']) && !empty($_POST['email'])){

	//connect to DB
	include("db_connect.php");
	
	$email = mysqli_real_escape_string($mydb, $_POST['email']);
	
	$result = mysqli_query($mydb, "SELECT Name, Major, Bio FROM tutor WHERE Email='$email'");

	if($result){
		$row = mysqli_fetch_array($result);
		$name = $row["Name"];
		$major = $row["Major"];
		$bio = $row["Bio"];
	
		$response["name"] = $name;
		$response["bio"] = $bio;
		$response["major"] = $major;
		$response["success"] = 1;
		$response["message"] = "Profile updated!";
		echo json_encode($response);
	}
	//Failed to update
	else{
		$response["success"] = 0;
		$response["message"] = "Failed to update information in the server!";
		echo json_encode($response);
	}
}else{
	$response["success"] = 0;
	$response["message"] = "Please fill out all required field(s)!";
	echo json_encode($response);
}
?>
