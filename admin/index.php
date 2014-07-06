<?php
	include('../config.php'); // het laden van de database
	
	session_start(); // het starten van session
	$user = $_SESSION['username']; // aangeven van de variable user inhoudt
	$pass = $_SESSION['sha1_password']; // aangeven van de variable pass inhoudt
	
	// deze querie is er om te kijken of de gevenens in de database voor komen
	$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error());
	// hier wordt gekeken of de gegevens kloppen
	$rowCheck = mysql_num_rows($result);
	if($rowCheck > 0){
		// als de gegevens bestaan word je doorverwezen naar de pagina van de bestellingen
		die(header("Location: bestellingen/"));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra Admin - Login</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../css/admin.css" media="all" />
	<script src="../js/jquery.js" type="text/javascript"></script>
	<script src="../js/script.js" type="text/javascript"></script>
</head>
<body class="login">
	<!-- dit is het het loginform -->
	<a href="../">← Terug naar de homepage</a>
	<h2>Cosa Nostra Admin</h2>
	<form action="sessions/create/" method="post"> 
		<p><input type="text" class="input" value="Gebruikersnaam" name="gebruikersnaam" id="login-gebruikersnaam" onclick="this.value='';" onblur="this.value=!this.value?'Gebruikersnaam':this.value;" /></p> 		
		<p><input type="text" class="password password-clear" value="Wachtwoord" name="wachtwoord" autocomplete="off" id="login-pass-clear" /><input type="password" class="password" value="" name="wachtwoord" id="login-pass" autocomplete="off" /></p> 
		<p><input id="login-btn" class="btn-login" type="submit" value="login" /></p>
		<div class="clear"></div>
	</form>
</body>
</html>