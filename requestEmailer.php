<?php

if(isset($_POST['emailTo']) && isset($_POST['emailFrom']) && isset($_POST['message'])){

	include("db_connect.php");

	$emailTo = mysqli_real_escape_string($mydb, $_POST['emailTo']);
	$emailFrom = mysqli_real_escape_string($mydb, $_POST['emailFrom']);
	$message = mysqli_real_escape_string($mydb, $_POST['message']);

	require_once "Mail.php";

	include("email_config.php");
	
	$to = $emailTo;
	$subject = 'Tutoring request!';
	$body = '
	You have a tutoring request! Here is what they said:
	
	'.$message.'

	To respond, email '.$emailFrom.'.

	Sincerely, Ambi';

	$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);
	
	$smtp = Mail::factory('smtp',
		array ('host' => $host,
			'port' => $port,
			'auth' => true,
			'username' => $username,
			'password' => $password));

	$mail = $smtp->send($to, $headers, $body);

	if(PEAR::isError($mail)){
		$response["success"] = 0;
		$response["message"] = $mail->getMessage();
		echo json_encode($response);
	}
	else{
		$result = mysqli_query($mydb, "INSERT INTO message(TutorEmail,TuteeEmail,Message) 
				VALUES('".$emailTo."','".$emailFrom."','".$message."')");

		$response["success"] = 1;
		$response["message"] = "Request Sent!";
		echo json_encode($response);
	}
}
else{
	$response["success"] = 0;
	$response["message"] = "Please fill out all required fields!";
	echo json_encode($response);
}
?>
