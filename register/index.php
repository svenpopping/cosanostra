<?php
include('../config.php'); // het laden van de database

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

//lees de adres gegevens uit het ingevulde formulier
$naam = ucwords(addslashes($_REQUEST['naam'])); //de functie ucwords() zorgt dat de eerste letter 
$straat = ucwords(addslashes($_REQUEST['straat']));
$huisnummer = addslashes($_REQUEST['huisnr']);
$postcode = strtoupper(str_replace(' ', '', $_REQUEST['postcode'])); //de functie strtoupper() zorgt er voor dat de postcode automatisch in hoofdletters wordt geschreven, bovendien worden eventuele spaties weg gehalad met een str_replace() functie.
$woonplaats = addslashes($_REQUEST['woonplaats']);

$rk_nummer = addslashes($_REQUEST['registreer-nummer']);
$email = addslashes($_REQUEST['email']);
$pass = sha1($_REQUEST['password']);
$validation = $_REQUEST['validation'];

$klanten_id =  randomString (); //random id strings worden aangemaakt.
$kaart_id =  randomString ();

//email bericht voor het activeren van een klantenkaart.
$message='Beste '.$_REQUEST['naam'].', 

Hartelijk dank voor uw aanmelding:
----------------------------------------------------------------
Emailadres: '.$_REQUEST['email'].'
Wachtwoord: '.$_REQUEST['validation'].'
Rekeningnummer: '.$_REQUEST['registreer-nummer'].'
Activatie code: '.$klanten_id.'
----------------------------------------------------------------
Klik op deze link om u klantenkaart te activeren:
http://popizza.site88.net/register.php?id='.$klanten_id.'.


Tot ziens bij Cosa Nostra';

$query = mysql_query("SELECT * FROM klantenkaart WHERE email='{$email}'") or die(mysql_error());
$result = mysql_fetch_array($query);

// Activering 
if($_GET['id'] != ""){ //Als het id van een registratie is aangegeven wordt de activering loop uitgevoerd.
	$id = $_GET['id'];
	if($id != "") {
		//als er een juist id is opgegeven wordt de klantenkaart geactiveerd en wordt er doorverwezen naar een succes pagina.
		mysql_query("UPDATE klantenkaart SET acti=1 WHERE klanten_id='{$id}'") or die(mysql_error());
		die(header('Location: success/?activatie'));
	} else {
		//is er geen juist id opgegeven krijgt de gebruiker een error.
		die(header('Location: success/?error=Het+activeren+van+uw+account+is+mislukt.+Probeert+het+opnieuw+of+neem+contact+op+via%3A+info%40cosanostra.nl'));
	}
}
//Registratie
else{ //wanneer er geen id is opgegeven wordt de registreer loop uitgevoerd.
	if($naam != "Voor en Achternaam" && $straat != "Straatnaam" && $huisnummer != "Huisnr" && $postcode != "Postcode" && $woonplaats != "Woonplaats" && $rk_nummer != "Rekeningnummer (xxx-xxxxxxx-xx)" && $email != "Emailadres") {
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
		} else {	
			//woont de persoon minder dan een kwartier verwijderd van de pizzeria dan wordt er door gegaan met de loop.
			if($result['email'] != $email){ //als het email adres nog niet bij ons bekend is wordt er door gegaan met de aanmaak van een klantenkaart.
				if($pass == sha1($validation)) {
					$result = mysql_query("SELECT * FROM klanten WHERE huisnr='{$huisnummer}' AND postcode='{$postcode}'");
					$rowCheck = mysql_num_rows($result);
					if($rowCheck <= 0){ //als de klant al eens eerder een bestelling heeft geplaatst voordat hij een klantenkaart heeft aangemaakt worden beide de klantenkaart en de klant gegevens zelf opgeslagen.
						mysql_query("INSERT INTO klantenkaart (kaart_id, klanten_id, rekeningnr, email, pass, acti) VALUES ('{$kaart_id}', '{$klanten_id}', '{$rk_nummer}', '{$email}', '{$pass}', 0)") or die(mysql_error());
						mysql_query("INSERT INTO klanten (klanten_id, naam, straat, huisnr, postcode, woonplaats) VALUES ('{$klanten_id}', '{$naam}', '{$straat}', '{$huisnummer}', '{$postcode}', '{$woonplaats}')") or die(mysql_error());
						mail($email, "Activeren klantenkaart", $message, "From: admin@popizza.site88.net");
					} else { //geeft de gebruiker wel eerder een bestelling geplaatst wordt alleen een klantenkaart aangemaakt.
						$gegevens = mysql_fetch_array($result);
						mysql_query("INSERT INTO klantenkaart (kaart_id, klanten_id, rekeningnr, email, pass, acti) VALUES ('{$kaart_id}', '{$gegevens['klanten_id']}', '{$rk_nummer}', '{$email}', '{$pass}', 0)") or die(mysql_error());
						mail($email, "Activeren klantenkaart", $message, "From: admin@popizza.site88.net");
					}
					die(header('Location: success/?true'));
				} else {
					die(header('Location: success/?error=De+wachtwoorden+komen+niet+over+een.+Ga+terug+naar+de+homepagina+en+probeer+het+opnieuw.'));
				}
			} else { //is het email adres wel bij ons bekend krijgt de gebruiker een error.
				die(header('Location: success/?error=Per+emailadres+wordt+maar+%E9%E9n+klantenkaart+verstrekt!'));			
			}
		}
	} else { 
		die(header('Location: success/?error=U+hebt+niet+alle+velden+goed+ingevuld.+Ga+terug+naar+de+homepagina+en+probeer+het+opnieuw.'));
	}
}




?>