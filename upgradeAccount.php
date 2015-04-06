<?php

$response = array();

if(isset($_POST["email"] && isset($_POST["experience"]) && isset($_POST["price"]){

	include("db_connect");

	$email = mysqli_real_escape_string($mydb, $_POST["email"]);	
	$experience = mysqli_real_escape_string($mydb, $_POST["experience"]);	
	$price = mysqli_real_escape_string($mydb, $_POST["price"]);	
	$name;
	$gender;
	$picture;
	$major;
	$bio;

	$queryResult = mysqli_query($mydb, "SELECT Name, Gender, Picture, Major,
			Bio FROM tutee WHERE Email='".$email."'");

	if($queryResult){

		$row = mysqli_fetch_array($queryResult);
		$name = $row["Name"];
		$gender = $row["Gender"];
		$picture = $row["Picture"];
		$major = $row["Major"];
		$bio = $row["Bio"];

		$insertResult = mysqli_query($mydb, "INSERT INTO tutor VALUES('".$email."',
			'".$name."','".$gender."','".$picture."','".$major."','".$price."',
			'".$bio."','".$expereince."',0");

		if($insertResult){
			$response["success"] = 1;
			$response["message"] = "User successfully upgraded!";
			echo json_encode($response);
		}else{
			$response["success"] = 0;
			$response["message"] = "Error: Could not upgrade user";
			echo json_encode($response);
		}

	}else{
		$response["success"] = 0;
		$response["message"] = "Error: Couldn not find user in database";
		echo json_encode($response);
	}

}else{

	$response["success"] = 0;
	$response["message"] = "Please fill out all required fields.";
	echo json_encode($response);
}

?>
