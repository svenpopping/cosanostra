<?php
//hier worden de gegevens die je in het login-form hebt ingevoerd omgezet naar een variabele
$user = $_REQUEST['gebruikersnaam'];
$pass = sha1($_REQUEST['wachtwoord']);

//hier wordt gecontroleerd dat de gebruiker wel via het login-form hier is gekomen.
//hij wordt terug gestuurd naar het login-form als dat niet zo is.
if (!isset($user) || !isset($pass)) {
header( "Location: ../../" );
}
//hier wordt gekeken of de velden niet leeg zijn
elseif (empty($user) || empty($pass)) {
header( "Location: ../../" );
}
else{
include('../../../config.php'); // het laden van de database
$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error()); // dit is de query om te kijken of de invoerde gegevens wel kloppen

//hier wordt gekeken of er een rij is met de ingevoerde gegevens. zo niet id het aantal rijen 0 anders in de zijn de rijen 1 of meet
$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){ 
// als de gegevens kloppen...
while($row = mysql_fetch_array($result)){
  // worden de variabelen in sessions opgeslagen.
  session_start();
  $_SESSION['username'] = $user;
  $_SESSION['sha1_password'] = $pass;

  //als je goed bent ingelogd dan word je naar de map bestellingen door verwezen.
  header("Location: ../../bestellingen/");
  }
  }
  else {
  // als er dus iets fout is aan de login dan komt er te staan of je opnieuw wil inloggen 
  echo 'Incorrect login name or password. Please try again. <a href="../../">Go back</a>';
  }
  } 
?>
