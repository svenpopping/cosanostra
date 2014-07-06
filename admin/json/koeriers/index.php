<?php
header('Cache-Control: no-cache, must-revalidate'); //No-cache zorgt dat de JSON array elke keer opnieuw wordt geladen.
header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
header('Content-type: application/json'); //content type van de pagina wordt van PHP naar JSON veranderd.
?>
{"koeriers":[<?php
include('../../../config.php'); // het laden van de database
$query = mysql_query("SELECT * FROM koerier ORDER BY status, naam") or die(mysql_error()); //selecteren van alle koeriers, deze worden gesorteerd op status en daarna op naam.
$i = 0;
$num_rows = mysql_num_rows($query); //aantal koeriers wordt geteld
while ($result = mysql_fetch_array($query)) {
	$koerier_id = $result['koerier_id']; //koerier id wordt uit de database geladen
	$naam = $result['naam']; //naam van de koerier wordt uit de database geladen
	$status_id = $result['status']; //status van de koerier wordt uit de database geladen
	//status wordt omgezet van een nummer naar een normaal woord
	if($status_id == 0) {$status = "onbekend";}
	elseif($status_id == 1) {$status = "beschikbaar";}
	elseif($status_id == 2) {$status = "bezorgen";}
	elseif($status_id == 3) {$status = "afwezig";}
	else {$status = "onbekend"; $status_id = 0;}
	$i++;
	//gegevens uit de database worden omgezet naar een JSON formaat, de laatse koerier krijgt geen comma aan het einde van de string. Dit is nodig omdat Internet Explorer deze anders niet kan lezen.
	if($i < $num_rows) {
		echo '{"koerier_id":'.$koerier_id.', "naam":"'.$naam.'", "status":'.$status_id.', "status_text":"'.$status.'"},';
	} else {
		echo '{"koerier_id":'.$koerier_id.', "naam":"'.$naam.'", "status":'.$status_id.', "status_text":"'.$status.'"}';
	}
}
?>]}