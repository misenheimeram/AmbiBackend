<?php 

$response = array();

if(isset($_POST["available"])){

	include("db_connect.php");

	$result = mysqli_query($mydb, "SELECT * FROM tutor");

	$response["tutors"] = array();

	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

		$tutor = array();
		$tutor["email"] = $row["Email"];
		$tutor["name"] = $row["Name"];
		$tutor["gender"] = $row["Gender"];
		$tutor["picture"] = $row["Picture"];
		$tutor["major"] = $row["Major"];
		$tutor["price"] = $row["Price"];
		$tutor["bio"] = $row["Bio"];
		$tutor["experience"] = $row["Experience"];
		$tutor["availability"] = $row["Availability"];

		array_push($response["tutors"], $tutor);
	}
	$response["success"] = 1;
	echo json_encode($response);

else{
	$response["success"] = 0;
	$response["message"] = "Filter information not set";
	echo json_encode($response);
}
	mysqli_close($mydb);
?>
