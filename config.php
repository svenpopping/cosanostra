<?php
session_start(); //starten van een session zodat dit niet op elke pagina apart gedaan hoeft te worden.
mysql_connect("mysql2.000webhost.com", "a6268976_admin", "seSt3pec7Aju") or die(mysql_error()); //verbinding maken met de database.
mysql_select_db("a6268976_admin") or die(mysql_error()); //selecteren en verbinding maken met de database
?>