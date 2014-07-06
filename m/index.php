<?php
	include('../config.php'); // verbinden met de database
	
	// met dit stuk wordt gekeken of er niet al ingelogd is met goede gegevens
	session_start(); // het starten van sessions
	$user = $_SESSION['m_username']; // het omzetten van de session naar de variabele user
	$pass = $_SESSION['m_sha1_password']; // het omzetten van de session naar de variabele pass

	$result = mysql_query("SELECT * FROM koerier WHERE naam='{$user}' AND pass='{$pass}'") or die(mysql_error()); // deze query is om te controleren dat de gegevens die in de sessions staan kloppen 

	$rowCheck = mysql_num_rows($result); // door de rijen te tellen kun je controleren of de gegevens kloppen
	if($rowCheck > 0){
		die(header("Location: list/")); // hier wordt de je doorgestuurd naar de site waar je de bestellingen kan  lezen
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra</title>
	<meta name="viewport"
    	content="width=device-width,minimum-scale=1.0,maximum-scale=1.0" />
	<link rel="apple-touch-icon-precomposed" href="../images/mobile/apple-touch-icon.png"/>
	<link rel="apple-touch-startup-image" href="../images/mobile/splash.png"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />  
	<link rel="stylesheet" type="text/css" href="../css/mobile.css" media="all" />	
</head>
<body>
	<div id="nav-bar">
		<h1>Cosa Nostra</h1>
	</div>
	<?php
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"iphone")) { // met deze if wordt gekeken of je een iPhone of een iPod touch gebruikt
		   if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"safari")) { // en of je wel de goede internetbrowser gebruikt
		      ?>
		      <div id="content"><p>Deze app is speciaal ontwikkeld voor koeriers van Pizzeria Restaurant Cosa Nostra. <br /> Om als koerier te kunnen inloggen met deze app moet deze eerst worden geinstalleerd. Alstublieft, volg de instructies in de onderstaande popup.</p></div>
		      <div class="bubble"><p><img src="../images/mobile/apple-touch-icon.png" alt="Icon" />Installeer de Cosa Nostra app: druk op de pijl en dan op <b>'Zet in beginscherm'</b></p><div class="arrow"> </div></div>
		      <?
		   }else{ // als je niet de goede internet browser gebruikt kun je nog wel inloggen maar niet de app installeren

	?>
<!-- Dit is de loginform die je krijgt te zien als je een iPhone of een iPod touch gebruikt -->
<div class="login">
	<form action="sessions/create/" method="post"> 
		<p><input type="text" class="input" value="Gebruikersnaam" name="gebruikersnaam" id="login-gebruikersnaam" onclick="this.value='';" onblur="this.value=!this.value?'Gebruikersnaam':this.value;" /></p> 		
		<p><input type="password" class="password" value="Wachtwoord" name="wachtwoord" id="login-pass" autocomplete="off" onclick="this.value='';" onblur="this.value=!this.value?'Wachtwoord':this.value;"/></p> 
		<p><input id="login-btn" class="btn-login" type="submit" value="login" /></p>
		<div class="clear"></div>
	</form>
</div>

<?php
   }
}else{ // als je beide niet hebt dan krijg je dit te zien
   ?>
   <!-- dit is de code die je krijgt als je geen iPhone of iPod touch gebruikt --> 
   <div id="content"><p>Uw browser ondersteunt deze webapp niet. Mogelijk kunt u niet alle functies van deze app gebruiken. <br /> Voor het meest optimale resultaat, open deze link op een iPhone of iPod touch.</p></div>
   
   
   <div class="login">
   	<form action="sessions/create/" method="post"> 
   		<p><input type="text" class="input" value="Gebruikersnaam" name="gebruikersnaam" id="login-gebruikersnaam" onclick="this.value='';" onblur="this.value=!this.value?'Gebruikersnaam':this.value;" /></p> 		
   		<p><input type="password" class="password" value="Wachtwoord" name="wachtwoord" id="login-pass" autocomplete="off" onclick="this.value='';" onblur="this.value=!this.value?'Wachtwoord':this.value;"/></p> 
   		<p><input id="login-btn" class="btn-login" type="submit" value="login" /></p>
   		<div class="clear"></div>
   	</form>
   </div>
   <?php
}
?>
</body>
</html>