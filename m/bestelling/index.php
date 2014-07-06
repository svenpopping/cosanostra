<?php
	include('../../config.php'); // verbinden met de database
	// dit stuk hebben we al een keer uitgelegd. uitleg kijk bij het admingedeelte
	session_start();
	$user = $_SESSION['m_username'];
	$pass = $_SESSION['m_sha1_password'];
	if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../"));
	}
	elseif (empty($user) || empty($pass)) {
	die(header("Location: ../"));
	}
	else{
		$result = mysql_query("SELECT * FROM koerier WHERE naam='{$user}' AND pass='{$pass}'") or die(mysql_error());

		$rowCheck = mysql_num_rows($result);
		if($rowCheck <= 0){
			die(header("Location: ../"));
		}
	}
// deze fuctie is ook al vaker uitgelegd staat ook in het admingedeelte
function ago($time)
{
   $periods = array("seconden", "minuten", "uren", "dagen", "weken", "maanden", "jaren", "decenium");
   $lengths = array("60","60","24","7","4.35","12","10");
   $now = time();
       $difference     = $now - $time;
       $tense         = "geleden";
   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }
   $difference = round($difference);
   return "$difference $periods[$j] geleden";
}

// hier worden wat gegevens om gezet naar een paar variabele
$bestelling_id = $_REQUEST['id'];
$koerier_id = $_SESSION['koerier_id'];

$query = mysql_query("SELECT * FROM bestelling WHERE bestelling_id='{$bestelling_id}'") or die(mysql_error()); // met deze query gekeken welke bestellingen de koerier moet bezorgen.
// met deze loop worden de klanten gegevens uit de database geladen
while ($result = mysql_fetch_array($query)) {
	$klanten_id = $result['klanten_id'];
	$time_ago = ago($result['timestamp']);
	$tot_prijs = $result['tot_prijs'];
	$json = $result['json'];
	$bestelling = json_decode($json, true);
	$betaald = $result['betaald'];
	$query = mysql_query("SELECT * FROM klanten WHERE klanten_id='{$klanten_id}'") or die(mysql_error());
	while ($result = mysql_fetch_array($query)) {
		$naam = stripslashes($result['naam']);
		$straat = stripslashes($result['straat']);
		$huisnr = $result['huisnr'];
		$postcode = $result['postcode'];
		$woonplaats = $result['woonplaats'];
	}
}
// hier wordt de link gemaakt voor het plaatje van de map
$gmaps_url = "http://maps.google.com/maps/api/staticmap?size=300x60&zoom=14&maptype=roadmap\&markers=size:small|color:red|".urlencode($straat)."+{$huisnr}+{$postcode}+".urlencode($woonplaats)."+NL&sensor=false";
// hier wordt de link gemaakt voor als je op de kaart klikt
$map_url = "http://maps.google.nl/maps?f=q&source=s_q&hl=nl&q=".urlencode($straat)."+{$huisnr}+{$postcode}+".urlencode($woonplaats)."+NL";	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra</title>
	<meta name="viewport" content="width=320, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon-precomposed" href="../../images/mobile/apple-touch-icon.png"/>
	<link rel="apple-touch-startup-image" href="../../images/mobile/splash.png"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />  
	<link rel="stylesheet" type="text/css" href="../../css/mobile.css" media="all" />
</head>
<body>
	<div id="nav-bar">
		<h1>Cosa Nostra</h1>
		<a href="javascript:window.location='../list/'" class="back">Terug</a>
		<a href="javascript:window.location='../sessions/destroy/'" class="logout">Loguit</a>
	</div>
	<div id="indetail">
		<div class="header">
			<h2><?php echo $naam;?></h2>
			<span class="timeago"><?php echo $time_ago;?></span>
			<span class="arrow"></span>
		</div>
	</div>
	
	<div id="content">
		<p><?php
			// met deze loop worden de pizza's uit de array gehaald.
			foreach ($bestelling['bestelling'] as $i => $item){
				echo $item['pizza_naam'];
				if(isset($item['opmerkingen'])) {
					echo ' <span class="opmerking">('.urldecode($item['opmerkingen']).')</span>';
				}
				echo ", ";
			}
		?></p>
		<a href="javascript:window.location='<?php echo $map_url;?>'" class="map"><img src="<?php echo $gmaps_url;?>" alt="Google Maps" /></a>
		<span class="location"><?php echo $straat . " " . $huisnr . ", " . $postcode  . " " . $woonplaats; ?></span>
	</div>
	
	<div id="voltooien">
		<?php 
		// hier wordt gekeken wat de prijs is of als er betaald is
		if($betaald != 1) {
			echo '<span class="totaal-prijs">Totaal Prijs: €'.$tot_prijs.'</span>';
		} else {
			echo '<span class="totaal-prijs">Klant heeft al betaald.</span>';
		}
		?>
		<a href="javascript:window.location='voltooien?id=<?php echo $bestelling_id;?>'" class="btn-voltooien">Bestelling Voltooien</a>
	</div>
</body>
</html>