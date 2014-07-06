<?php
	//start the session
	session_start();
	
	//check to make sure the session variable is registered
	include('../../../config.php');
	
	session_start();
	$user = $_SESSION['m_username'];
	$pass = $_SESSION['m_sha1_password'];
	$koerier_id = $_SESSION['koerier_id'];
	if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../../"));
	}
	//check that the form fields are not empty, and redirect back to the login page if they are
	elseif (empty($user) || empty($pass)) {
	die(header("Location: ../../"));
	}
	else{
		$result = mysql_query("SELECT * FROM koerier WHERE koerier_id='{$koerier_id}'") or die(mysql_error());

		$rowCheck = mysql_num_rows($result);
		if($rowCheck <= 0){
			die(header("Location: ../../"));
		}
	}

	session_unset();
	session_destroy();
	mysql_query("UPDATE koerier SET status=3 WHERE koerier_id='{$koerier_id}'") or die(mysql_error());
	
	header( "Location: ../../" );
?>