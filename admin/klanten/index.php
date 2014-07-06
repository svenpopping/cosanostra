<?php
include('../../config.php'); //het laden van de database

//in dit stuk wordt gekeken of er wel is ingelogd en of dit wel is met geldige gegevens
session_start();
$user = $_SESSION['username']; // het ophalen welke gegevens er in de session username staat
$pass = $_SESSION['sha1_password']; // het op halen welke gegevens er in de session sha1_password staat

// hier staar wat er gebeurd als de de sessions niet bestaan
// als er niks in staat word je doorgestuurd naar de login pagina
if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../"));
}
// hier wordt gekeken of er wel iets in de sessions staat
elseif (empty($user) || empty($pass)) {
	die(header("Location: ../"));
} 
// hier wordt gekeken of de gegevens wel kloppen met wat er in de database staat
else{
	// met deze querie wordt gekeken of beide gegevens in de database voor komen
	$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error());
	//hier wordt gekeken hoeveel regels er zijn met deze gegevens
	$rowCheck = mysql_num_rows($result);
	// als het aantal rijen 0 is zijn de gegevens die in de sessions staan niet correct en wordt je terug verwezen naar de login
	if($rowCheck <= 0){
		die(header("Location: ../"));
	}
}

// hier wordt gekeken of er in de url iets zit wat verwijderd moet worden
if($_GET['delete'] != ""){
	$delete = $_GET['delete']; //hier wordt het delete 
	mysql_query("DELETE FROM klanten WHERE klanten_id = '{$delete}'"); // de querie om de klant uit de klanten tabel te verwijderen
	mysql_query("DELETE FROM klantenkaart WHERE klanten_id = '{$delete}'"); // de querie om de klantenkaart van deze klant te verwijderen
	mysql_query("DELETE FROM bestelling WHERE klanten_id = '{$delete}'"); // de querie om de bestellingen van de klant te verwijderen
}

// hier wordt gekeken welke van de querie hij moet gebruiken
// als het type kaart is moet er de querie gebruikt worden die alleen de klantenkaarten laat zien
// en anders de querie voor alle klanten
if ($_GET['type'] != "kaart") {
	// met deze querie worden alle klanten geselecteerd
	$query = mysql_query("SELECT * FROM klanten") or die(mysql_error());
	// hier wordt geteld hoeveel rijen er zijn, dus hoeveel klanten er intotaal zijn
	$rowCheck = mysql_num_rows($query);
} else {
	// anders worden de klanten met een klanten kaart geselecteerd
	$query = mysql_query("SELECT * FROM klanten INNER JOIN klantenkaart ON klanten.klanten_id = klantenkaart.klanten_id") or die(mysql_error());
	// en wordt het aantal rijen hier van beplaad, dus hoevel klanten er zijn met een klantenkaart 
	$rowCheck = mysql_num_rows($query);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra Admin - Bestellingen</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../../css/admin.css" media="all" />
	<script src="../../js/jquery.js" type="text/javascript"></script>
</head>
<body>
<span class="content">
	<div id="header">
		<p>Hallo <?php echo $user; ?>, <a href="../sessions/destroy/">Log Uit</a></p><div class="clear"></div>
		<h2>Cosa Nostra Admin</h2>
		<ul id="nav">
			<li><a href="../bestellingen/" class="nav-bestellingen">Bestellingen</a></li>
			<li><a href="#" class="nav-klanten">Klanten</a></li>
			<li><a href="../archief/" class="nav-archief">Archief</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<h1>Klanten - (<?php echo $rowCheck; ?>)</h1> <!-- In deze h1 worden een getal afgebeeld dat afhankelijk in van of de knop "alleen klantenkaart geactiveerd is" het is dus of het totaal aantal klanten of het aantal klanten met een klantenkaart -->
	<div class="clear"></div>	
		<div id="aan-uit">Alleen klanten met een klantenkaart: <?php if($_GET['type'] == "kaart") { ?><a href="./?sort=<?php echo $_GET['sort'];?>"><span class="uit" style="background: url('../../images/sprite.png') -80px -412px;"><?php } else { ?><a href="?type=kaart&sort=<?php echo $_GET['sort'];?>"><span class="uit" style="background: url('../../images/sprite.png') 0px -412px;"><?php } ?></span></a></div> <!-- dit is de knop voor het selecteren van de klanten met een klantenkaart -->
		<ul id="bestellingen"><!-- dit is de list met de klanten -->
			<?php
			// hier wordt bepaald welke klantengegevens geladen moeten worden, want dit is verschillende bij de twee soorten klanten met klantenkaart en alle klanten
			// als het type kaart (dus klanten met klantenkaart) is dan komt deze regel er boven te staan.
			if($_GET['type'] == "kaart") {
				// dit is de bovenste regel (de regel met de kolommen waar op gesorteerd kan worden)
				echo '<li><p><span id=\'naam\'><a href="?type=kaart&sort=naam">Naam:</a></span><span id=\'straat\'><a href="?type=kaart&sort=straat">Straatnaam:</a></span><span id=\'postcode\'><a href="?type=kaart&sort=postcode">Postcode:</a></span><span id=\'woonplaats\'><a href="?type=kaart&sort=woonplaats">Woonplaats:</a></span><span id=\'kaart\'>Klantenkaart:</span></p></li>';
				// als sort waarde "" heeft dan wordt het om gezet in "naam" en als sort al iets is dan wordt dit in de variable $sort gezet.
				if($_GET['sort'] == "") { $sort="naam"; } else { $sort = $_GET['sort']; }
				// de querie voor het selecteren van de klanten met een kantenkaart
				$query = mysql_query("SELECT * FROM klanten INNER JOIN klantenkaart ON klanten.klanten_id = klantenkaart.klanten_id ORDER BY ".$sort."") or die(mysql_error());
				// aan de hand van deze loop worden de gegevens in de lijst gezet dit gebeurt aan de hand van li-tags
				while($result = mysql_fetch_array($query)){
					echo "<li><p><span id='naam'>".$result['naam']."</span><span id='straat'>".$result['straat']." ".$result['huisnr']."</span><span id='postcode'>".$result['postcode']."</span><span id='woonplaats'>".$result['woonplaats']."</span><span id='kaart'>Ja</span><a href='?type=kaart&delete=".$result['klanten_id']."'\'><span id='delete' class='icon-small' style='background-position: -96px 0;'></span></a></p></li>";
					// deze div zorgt ervoor dat de tekst mooi onder elkaar komt te staan
					echo "<div class='clear'></div>";				
				}
			// ander komt deze regel er boven te staan (voor de alle klanten)
			} else {
				// dit is de bovenste regel (de regel met de kolommen waar op gesorteerd kan worden) deze is iets ander dan de regel voor de klantenkaart
				echo '<li><p><span id=\'naam\'><a href="?sort=naam">Naam:</a></span><span id=\'straat\'><a href="?sort=straat">Straatnaam:</a></span><span id=\'postcode\'><a href="?sort=postcode">Postcode:</a></span><span id=\'woonplaats\'><a href="?sort=woonplaats">Woonplaats:</a></span><span id=\'kaart\'>Klantenkaart:</span></p></li>';
				// als sort waarde "" heeft dan wordt het om gezet in "naam" en als sort al iets is dan wordt dit in de variable $sort gezet
				if($_GET['sort'] == "") { $sort="naam"; } else { $sort = $_GET['sort']; }
				// de querie om alle klanten uit de tabel te laden deze worden gesorteerd op de variable $sort
				$query = mysql_query("SELECT * FROM klanten ORDER BY ".$sort."") or die(mysql_error());
				// dit is bijna dezelfde loop als bij de klanten met klantenkaart
				while($result = mysql_fetch_array($query)){
					// dit is de querie die kijk of de klanten een klantenkaart heeft.
					$query_kaart = mysql_query ("SELECT * FROM klantenkaart WHERE klanten_id='{$result['klanten_id']}'") or die(mysql_error());
					// hier wordt bepaald of de klant een klanten kaart heeft of niet
					$rowCheck = mysql_num_rows($query_kaart);
					// dit wordt vertoond als de klant een klantenkaart heeft
					if($rowCheck > 0){
						echo "<li><p><span id='naam'>".$result['naam']."</span><span id='straat'>".$result['straat']." ".$result['huisnr']."</span><span id='postcode'>".$result['postcode']."</span><span id='woonplaats'>".$result['woonplaats']."</span><span id='kaart'>Ja</span><a href='?delete=".$result['klanten_id']."'\'><span id='delete' class='icon-small' style='background-position: -96px 0;'></span></a></p></li>";
						echo "<div class='clear'></div>";
					// dit wordt getoond als de klant geen klantenkaart heeft
					} else {
						echo "<li><p><span id='naam'>".$result['naam']."</span><span id='straat'>".$result['straat']." ".$result['huisnr']."</span><span id='postcode'>".$result['postcode']."</span><span id='woonplaats'>".$result['woonplaats']."</span><span id='kaart'>Nee</span><a href='?delete=".$result['klanten_id']."'\'><span id='delete' class='icon-small' style='background-position: -96px 0;'></span></a></p></li>";
						echo "<div class='clear'></div>";
					}
					
				}
			}
			?>
		</ul>
		<div id="footer">Praktische Opdracht Informatica - Sietse de Boer, Bart Falkena, Rene Hiemstra, Sven Popping, Bouke Regnerus</div>
</span>
</body>
</html>