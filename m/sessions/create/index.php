<?php
$user = $_REQUEST['gebruikersnaam'];
$pass = sha1($_REQUEST['wachtwoord']);
//check that the user is calling the page from the login form and not accessing it directly
//and redirect back to the login form if necessary
if (!isset($user) || !isset($pass)) {
header( "Location: ../../" );
}
//check that the form fields are not empty, and redirect back to the login page if they are
elseif (empty($user) || empty($pass)) {
header( "Location: ../../" );
}
else{
//convert the field values to simple variables

//add slashes to the username and md5() the password
$user = $_REQUEST['gebruikersnaam'];
$pass = sha1($_REQUEST['wachtwoord']);

include('../../../config.php');
$result = mysql_query("SELECT * FROM koerier WHERE naam='{$user}' AND pass='{$pass}'") or die(mysql_error());

//check that at least one row was returned
$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
while($row = mysql_fetch_array($result)){
  $koerier_id = $row['koerier_id'];
  //start the session and register a variable
  session_start();
  
  $_SESSION['m_username'] = $user;
  $_SESSION['m_sha1_password'] = $pass;
  $_SESSION['koerier_id'] = $koerier_id;
  
  $result_test = mysql_query("SELECT * FROM bestelling WHERE status=2 AND koerier_id='{$koerier_id}'") or die(mysql_error());
  $rowCheck_test = mysql_num_rows($result_test);
  echo $rowCheck_test;
  if($rowCheck_test <= 0){
	mysql_query("UPDATE koerier SET status=1 WHERE koerier_id='{$koerier_id}'") or die(mysql_error());
  } 
  else {
	mysql_query("UPDATE koerier SET status=2 WHERE koerier_id='{$koerier_id}'") or die(mysql_error());
  }
  
  //successful login code will go here...
  
  header("Location: ../../list/");
  
  //we will redirect the user to another page where we will make sure they're logged in
  //header( "Location: checkLogin.php" );
  }
  }
  else {
  //if nothing is returned by the query, unsuccessful login code goes here...
  echo 'Incorrect login name or password. Please try again.';
  }
} 
?>
