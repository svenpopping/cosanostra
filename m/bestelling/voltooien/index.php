<?php
// met dit script wordt de bestelling voltooid
include('../../../config.php'); 
session_start();
$bestelling_id = $_GET['id'];
$koerier_id = $_SESSION['koerier_id'];
mysql_query("UPDATE bestelling SET status=3,betaald=1 WHERE bestelling_id='{$bestelling_id}'") or die(mysql_error());
$result = mysql_query("SELECT * FROM bestelling WHERE status='2' AND koerier_id='{$koerier_id}'") or die(mysql_error());
$rowCheck = mysql_num_rows($result);
if($rowCheck <= 0){
	mysql_query("UPDATE koerier SET status=1 WHERE koerier_id='{$koerier_id}'") or die(mysql_error());
}
header('Location: ../../list/');
?>