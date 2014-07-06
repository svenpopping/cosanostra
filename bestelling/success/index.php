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
} else {
?>
	<h1>Cosa Nostra</h1>
	<div>
		<h2>Bedankt voor uw bestelling,</h2>
		<p>Een van onze koeriers zal uw bestelling zo spoedig mogelijk op het door u aangegeven adres bezorgen.</p>
		<a href="../../" class="btn-yellow">Terug naar de homepage</a> 
		<h3>Eet smakelijk en tot ziens!</h3>
	</div>
<?php
}
?>
</body>
</html>
