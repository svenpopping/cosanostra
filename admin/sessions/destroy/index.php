<?php
	// het starten van de session
	session_start();
	include('../../../config.php'); // het laden van de databse
	
	// het controleren van de gegevens of ze wel kloppen. dit is voor de veiligheid
	// voor uitleg gaan vaan de map klanten.
	session_start();
	$user = $_SESSION['username'];
	$pass = $_SESSION['sha1_password'];
	if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../../"));
	}
	elseif (empty($user) || empty($pass)) {
	die(header("Location: ../../"));
	}
	else{
		$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error());

		$rowCheck = mysql_num_rows($result);
		if($rowCheck <= 0){
			die(header("Location: ../../"));
		}
	}
	
	session_unset(); // het deactiveren van de sessions 
	session_destroy(); // het verwijderen van de sessions
	
	header("Location: ../../"); // en dan word je weer terug gestuurd naar de login-pagina
?>