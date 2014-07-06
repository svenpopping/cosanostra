<?php
include('../config.php'); // het laden van de database

// deze fuctie is er om de lege stukken uit de array halen
function cleanupArray($array) {
	$new_array = array();
	foreach ($array as $key => $value) {
		if (!empty($value)) {
			if (is_array($value)) {
				$value = cleanupArray($value, 0);		
				if (count($value) == 0) {
					continue;
				}
			} else {
				$value = trim(strip_tags($value));
				if (empty($value)) {
					continue;
				}
			}		
			$new_array[$key] = $value;
		}
	}
	if (!empty($new_array)) {
		$new_array = array_merge_recursive($new_array);
	}
return $new_array;
}

// met deze fuctie wordt een random string gemaakt
function randomString () {
	$chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // dit zijn de karakters waaruit de string mag bestaan
	srand((double)microtime()*1000000);  
	$i = 0; 
	$pass = '' ; // dit is het begin van de sting
	while ($i <= 7) { // dit is de loop die de random string van 7 cijfers maakt
		$num = rand() % 33; // hier wordt een willekeurig getal gekozen
		$tmp = substr($chars, $num, 1); // hier wordt het randomkarakter bepaald, dus welke het is. dit gebeurt door met de variablee $num
		$pass = $pass . $tmp; // hier wordt het random karakter bij de rest gevoegd
		$i++; // terug naar het begin
	}
	return $pass;
}

$tot_prijs = $_REQUEST['prijs']; //vraag de totaalprijs op die gepost wordt door het ingevulde formulier.

$string = urldecode($_REQUEST['json-bestelling']); //vraag de bestelling op in een JSON array formaat.
$json = json_decode($string, true); //zet de JSON array om naar een normale array die door PHP gelezen kan worden.
if (!isset($string) || !isset($tot_prijs)) { //als er geen bestelling is opgegeven moet de pagina een error geven.
	die(header('Location: success/?error=U+heeft+geen+bestelling+geplaatst.'));
}
elseif (empty($string) || empty($tot_prijs)) {
	die(header('Location: success/?error=U+heeft+geen+bestelling+geplaatst.'));
}
else{
	//lees de adres gegevens uit het ingevulde formulier
	$naam = ucwords(addslashes($_REQUEST['naam'])); //de functie ucwords() zorgt dat de eerste letter automatisch een hoofdletter wordt.
	$straat = ucwords(addslashes($_REQUEST['straat']));
	$huisnr = $_REQUEST['huisnr'];
	$postcode = strtoupper(str_replace(' ', '', $_REQUEST['postcode'])); //de functie strtoupper() zorgt er voor dat de postcode automatisch in hoofdletters wordt geschreven, bovendien worden eventuele spaties weg gehalad met een str_replace() functie.
	$woonplaats = ucwords($_REQUEST['woonplaats']);
	$email = strtolower($_REQUEST['email']);
	$pass = $_REQUEST['pass'];
	
	$bestelling = addslashes(json_encode(cleanupArray($json))); //de uitgelezen bestelling wordt opnieuw omgezet naar een JSON array die geschikt is voor onze mysql database.
	$bestelling_id = randomString(); //een random id wordt aangemaakt.
	$timestamp = time();
	
	//als de gebruiker heeft ingelogd met een klantenkaart wordt de volgende if loop uitgevoerd.
	if($email != "Emailadres" && $pass != "") {
		$sha1_pass = sha1($pass); //het ingevulde wachtwoord wordt omgezet naar een (veilige) sha1() string.
		$result = mysql_query("SELECT * FROM klantenkaart WHERE email='{$email}' AND pass='{$sha1_pass}'") or die(mysql_error());

		$rowCheck = mysql_num_rows($result); //gegevens uit de database worden gecontroleerd met de ingevulde gegevens.
		if($rowCheck > 0){ //als de gebruiker juiste gegevens heeft ingevoerd wordt er door gegaan met de if loop.
			while($row = mysql_fetch_array($result)){
				$klanten_id = $row['klanten_id'];
				if($row['acti'] != 1) { //als de gebruiker zijn kantenkaart nog niet geeft geactiveerd wordt een error terug gestuurd.
					die(header('Location: success/?error=U+heeft+uw+klantenkaart+nog+niet+geactiveerd.'));
				} else {
					//wanneer alle gegevens van de klantenkaart kloppen wordt de bestelling opgeslagen in de database.
				mysql_query("INSERT INTO bestelling (bestelling_id, klanten_id, timestamp, tot_prijs, status, json, betaald) VALUES ('{$bestelling_id}','{$klanten_id}','{$timestamp}','{$tot_prijs}','1','{$bestelling}','1')") or die(mysql_error());
				}
			}
		}
		//komen de ingevoerde gegevens niet overeen met de database wordt er een error terug gestuurd.
		else {
			die(header('Location: success/?error=U+heeft+een+foutief+wachtwoord+ingevoerd.'));
				
		}
	}
	//heeft de gebruiker niet ingelogd met een klantenkaart wordt de else loop uitgevoerd.
	elseif($naam != "Voor en Achternaam" && $straat != "Straatnaam" && $huisnr != "Huisnr" && $postcode != "Postcode" && $woonplaats != "Woonplaats"){	
		$gmaps_url = "http://maps.google.com/maps/nav?q=from:53.11054,6.101425%20to:".urlencode($straat)."+{$huisnr}+{$postcode}+".urlencode($woonplaats)."+NL&oe=utf8"; //Google Maps API functie zie de afstand tussen twee locaties bepaald. In dit geval de afstand in seconden tussen pizzeria cosa nostra (adres van het Drachtster Lyceum) en het adres van de gebruiker.
		$gmaps = file_get_contents($gmaps_url);
		$distance_array = json_decode($gmaps, true);
		$afstand = $distance_array['Directions']['Duration']['seconds']; //afstand in seconden wordt uit de Google Maps API gelezen.
		if(empty($afstand)) {
			//als er geen afstand in de string staat geeft hij een error.
			die(header('Location: success/?error=Uw+adres+lijkt+niet+te+bestaan.'));
		}
		elseif (900 < $afstand) {
			//woont de persoon verder van de pizzeria verwijderd dan een kwartier rijden wordt er ook een error terug gestuurd.
			die(header('Location: success/?error=U+woont+meer+dan+een+kwartier+verwijderd+van+ons+restaurant.'));
		} 
		else {	
			//woont de persoon minder dan een kwartier verwijderd van de pizzeria dan wordt er door gegaan met de loop.
			$result = mysql_query("SELECT * FROM klanten WHERE huisnr='{$huisnr}' AND postcode='{$postcode}'");
			if(mysql_num_rows($result)){
				while($row = mysql_fetch_array( $result )) {
					$klanten_id = $row['klanten_id']; //als de persoon al eerder een bestelling heeft geplaatst wordt er gegeken of zijn adres al in de database staat. Als dit het geval is wordt zijn klanten_id uit de database geladen.
				} 
			} else {
				//plaatst de persoon voor het eerst een bestelling dan wordt er een random klanten_id aangemakat daarna worden zijn klanten gegevens opgeslagen in de database.
				$klanten_id = randomString();
				mysql_query("INSERT INTO klanten (klanten_id, naam, straat, huisnr, postcode, woonplaats) VALUES ('{$klanten_id}','{$naam}','{$straat}','{$huisnr}','{$postcode}','{$woonplaats}')") or die(mysql_error());
			}	
			//de bestelling wordt opgeslagen in de database.
			mysql_query("INSERT INTO bestelling (bestelling_id, klanten_id, timestamp, tot_prijs, status, json, betaald) VALUES ('{$bestelling_id}','{$klanten_id}','{$timestamp}','{$tot_prijs}','1','{$bestelling}','0')") or die(mysql_error());
		}	
	}
	//heeft de gebruiker helemaal geen gegevens ingevoerd wordt een error terug gestuurd.
	else {
		die(header('Location: success/?error=U+heeft+geen+gegeven+ingevoerd.'));
	}
}
//als de functies succesvol zijn voltooid wordt de persoon doorgestuurd naar een "succes" pagina met informatie over wanneer zijn pizza wordt bezorgd.
header('Location: success/?true');
?>