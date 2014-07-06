<?php
include('../../config.php'); // het laden van de database

function ago($time) // dit is ene functie die na gaat hoeveel tijd het geleden is
{
   $periods = array("seconden", "minuten", "uren", "dagen", "weken", "maanden", "jaren", "decenium"); // dit zijn de verschillende tijdsoorten
   $lengths = array("60","60","24","7","4.35","12","10"); // hiet mee wordt bepaald hoe lang iets duurt je moet het lezen als een minuut duurt 60 seconden, een uur duurt 60 minuten, enz.
   $now = time(); // hiet wordt gekeken hoelaat het nu is
       $difference     = $now - $time; // hiet wordt gekeken hoeveel seconden het verschil
       $tense         = "geleden"; // dit is de tag die er achter moet, dus bijvoorbeeld 10 minuten "geleden"
   // met deze loop wordt bepaald welke van de 8 tijdsoorten er moet staan.
   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }
   $difference = round($difference);
   return "$difference $periods[$j] geleden";
   
}
// dit is dezelfde code als in het bestand index.php in het mapje klanten. voor uitleg gaan naar klanten en klik op index.oho
session_start();
$user = $_SESSION['username'];
$pass = $_SESSION['sha1_password'];
if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../"));
}
elseif (empty($user) || empty($pass)) {
	die(header("Location: ../"));
} else{
	$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error());

	$rowCheck = mysql_num_rows($result);
	if($rowCheck <= 0){
		die(header("Location: ../"));
	}
}

// dit is de query die de bestellingen laad die zijn voltooid. ze worden gesorteerd op tijd.
$query = mysql_query("SELECT * FROM bestelling WHERE status=3 ORDER BY timestamp DESC") or die(mysql_error());
// hier worden het aantal rijen geteld.
$rowCheck = mysql_num_rows($query);
// dit is voor als je een bestelling wil verwijderen.	
if($_GET['delete'] != ""){
	$delete = $_GET['delete'];
	mysql_query("DELETE FROM bestelling WHERE bestelling_id = '{$delete}'");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra Admin - Bestellingen [Archief]</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../../css/admin.css" media="all" />
	<script src="../../js/jquery.js" type="text/javascript"></script>
	<style type="text/css">
	.timeago {
		float: right;
	}
	#delete {
		float: right;
	}
	</style>
</head>
<body>
<span class="content">
	<div id="header">
		<p>Hallo <?php echo $user; ?>, <a href="../sessions/destroy/">Log Uit</a></p><div class="clear"></div>
		<h2>Cosa Nostra Admin</h2>
		<ul id="nav">
			<li><a href="../bestellingen/" class="nav-bestellingen">Bestellingen</a></li>
			<li><a href="../klanten/" class="nav-klanten">Klanten</a></li>
			<li><a href="#" class="nav-archief">Archief</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<h1>Archief - (<?php echo $rowCheck; ?>)</h1>
	<div class="clear"></div>	
		<ul id="bestellingen">
			<?php
			// dit is voor het laden van de voltooide bestellingen
			if($rowCheck == 0){ // dit is voor als er nog geen bestellingen geplaast zijn
				echo "<li><p>Er zijn nog geen bestellingen afgerond</p></li>";
			} else { // als dat wel zo is dan worden de bestellingen uit de lijst geladen
				// dit is de loop waarmee de bestellingen uit de database worden gehaald.
				while($result = mysql_fetch_array($query)) {
					$json = json_decode($result['json'], true); // hier wordt de json-string gedecodeerd om hem uit te kunnen lezen. van de json-string wordt een array gemaakt.
					echo '<li><p>'; // dit is het begin van een list-item
					$count = count($json[bestelling]); // hier wordt geteld hoeveel pizza's er in de array staam
					for ($i = 0; $i < $count; $i++) { // dit is een loop om alle pizza's uit de array te halen
						echo $json[bestelling][$i][pizza_naam]; // hier wordt de pizzanaam afgebeeld
						if($json[bestelling][$i][opmerkingen] != ""){ // hier wordt gekeken of er ook opmerkingen bij de pizza zijn.
							echo ' <span class="opmerking">('.urldecode($json[bestelling][$i][opmerkingen]).')</span>'; // als er een opmerking is wordt hij hier afgebeeld
						}
						echo ", "; // anders komt er een komma
					}
					echo "<a href='?delete=".$result['bestelling_id']."'\'><span id='delete' class='icon-small' style='background-position: -96px 0;'></span></a>"; // dit is het kruisje aan het einde van de regel. dit is om de bestelling te verwijderen
					echo '<span class="timeago">'.ago($result['timestamp']).'</span>'; // word de "tijd geleden" afgebeeld
					echo '</p></li>'; // dit is het einde van de list				
				}
				// deze loop wordt herhaald tot dat alle gegevens uit de database zijn gehaald
			}
			?>
		</ul>
		<div id="footer">Praktische Opdracht Informatica - Sietse de Boer, Bart Falkena, Rene Hiemstra, Sven Popping, Bouke Regnerus</div>
</span>
</body>
</html>