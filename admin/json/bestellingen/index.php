<?php 
header('Cache-Control: no-cache, must-revalidate'); //No-cache zorgt dat de JSON array elke keer opnieuw wordt geladen.
header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
header('Content-type: application/json'); //content type van de pagina wordt van PHP naar JSON veranderd.
?>
{"bestellingen":[
<?php
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

include('../../../config.php');//het laden van de database
$query = mysql_query("SELECT * FROM bestelling WHERE status<>3 ORDER BY status, timestamp DESC") or die(mysql_error()); //selecteren van alle onvoltooide bestellingen (waar status ongelijk is aan 3)
$i = 0;
$num_rows = mysql_num_rows($query); //tellen van het aantal door de query terug gestuurde rijen
while ($result = mysql_fetch_array($query)) {
	$bestelling = substr($result['json'], 1, -1);  //het eerste en laatste karakter van de bestelling JSON string worden weg gehaald.
	$bestelling_id = $result['bestelling_id']; //bestelling id wordt uit de database geladen
	$status = $result['status']; //status van de bestelling worden uit de database geladen
	$timeago = ago($result['timestamp']); //timestamp wordt uit de database gehaald en omgezet met de timeago functie
	//gegevens worden met de volgende regels omgezet naar een JSON formaat, als de status gelijk is aan 2 (dat betekend dat de bestelling bezorgd wordt) dan wordt ook informatie over de koerier in de JSON code gezet.
	echo '{"bestelling_id": "' . $bestelling_id . '","status": ' . $status . ',"timeago": "'.$timeago.'",';
	if ($status == 2) {
		$query_koerier = mysql_query("SELECT * FROM koerier WHERE koerier_id='{$result['koerier_id']}'") or die(mysql_error());
		$result_koerier = mysql_fetch_array($query_koerier);
		echo '"koerier_naam": "'.$result_koerier['naam'].'",';
	}
	$i++;
	//het laatste gegeven in de json string krijgt geen comma, dit is nodig voor Internet Explorer.
	if($i < $num_rows) {
		echo $bestelling.'},';
	} else {
		echo $bestelling.'}';
	}
	
}
?>]}