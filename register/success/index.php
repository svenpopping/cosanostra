<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra - Pizzeria Restaurant</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../../css/success.css" media="all" />
</head>
<body>
<?php
if(!isset($_REQUEST['true'])) {
	$error = urldecode($_REQUEST['error']);
	if(!isset($_REQUEST['error'])) $error = 'Er is een onbekende fout opgetreden.';
?>	
		<h1>Cosa Nostra</h1>
		<div>
			<h2>Helaas,</h2>
			<p><?php echo $error; ?></p>
			<a href="../../" class="btn-yellow">Terug naar de homepage</a> 
			<h3>Alstublieft, probeer het opnieuw.</h3>
		</div>
<?php
} elseif(isset($_REQUEST['activatie'])) {
?>
	<h1>Cosa Nostra</h1>
	<div>
		<h2>Activering gelukt,</h2>
		<p>U kunt vanaf nu uw klantenkaart gebruiken voor het bestellen van pizza\'s.</p>
		<a href="../../" class="btn-yellow">Terug naar de homepage</a> 
		<h3>Veel plezier en tot ziens!</h3>
	</div>
<?php
} else {
?>
	<h1>Cosa Nostra</h1>
	<div>
		<h2>Bedankt voor het aanmelden,</h2>
		<p>U kunt vanaf nu gebruik maken van de vele voordelen die de Cosa Nostra klantenkaart bevat. U krijgt binnen enkele minuten een e-mail met daarin de nodige informatie. Bewaar deze goed!</p>
		<a href="../../" class="btn-yellow">Terug naar de homepage</a> 
		<h3>Veel plezier en tot ziens!</h3>
	</div>
<?php
}
?>
</body>
</html>
