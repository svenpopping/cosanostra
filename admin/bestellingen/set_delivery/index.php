<?php

$koerier_id = $_REQUEST['koerier']; //koerier id wordt opgevraagd
$bestelling_id = $_REQUEST['bestelling']; //bestelling id wordt opgevraagd

include('../../../config.php'); //database wordt geladen

//status van de bestelling en van de koerier worden veranderd in "bezorgen"
mysql_query("UPDATE bestelling SET status=2, koerier_id='{$koerier_id}' WHERE bestelling_id='{$bestelling_id}'") or die(mysql_error());
mysql_query("UPDATE koerier SET status=2 WHERE koerier_id='{$koerier_id}'") or die(mysql_error());

//als er geen fouten zijn gevonden wordt true terug gestuurd
return true;
?>