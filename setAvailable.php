<?php

$response = array();

if(isset($_POST["available"]) && isset($_POST["email"])){

	include("db_connect.php");

	$available = mysqli_real_escape_string($mydb, $_POST["available"]);
	$email = mysqli_real_escape_string($mydb, $_POST["email"]);

	$result = mysqli_query($mydb, "UPDATE tutor SET Availability='".$available."' WHERE Email='".$email."'");

	if($result){
		$response["success"] = 1;
		$response["message"] = "Availability updated!";
		echo json_encode($response);
	}else{
		$response["response"] = 0;
		$response["message"] = "Couldn't update!";
		echo json_encode($response);
	}


}else{
	$response["success"] = 0;
	$response["message"] = "Did not recieve availability status!";
	echo json_encode($response);
}


?>
