<?php
// dit is allemaal al een keer uitgelegd in het admingedeelte
include('../../config.php');
	
	session_start();
	$user = $_SESSION['m_username'];
	$pass = $_SESSION['m_sha1_password'];
	if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../"));
	}
	//check that the form fields are not empty, and redirect back to the login page if they are
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

$koerier_id = $_SESSION['koerier_id'];

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
		<a href="javascript:window.location='index.php'" title="Refresh"><h1>Cosa Nostra</h1></a>
		<a href="javascript:window.location='../sessions/destroy'" class="logout">Loguit</a>
	</div>
	
	<ul id="list">
		<?php
			$query = mysql_query("SELECT * FROM bestelling WHERE koerier_id='{$koerier_id}' AND status=2 ORDER BY timestamp DESC") or die(mysql_error()); // deze query wordt gebruikt om een lijst te maken van de bestellingen die een bezorger moet bezorgen
			// met deze loop wordt de lijst gemaakt
			while ($result = mysql_fetch_array($query)) {
				$bestelling_id = $result['bestelling_id'];
				$klanten_id = $result['klanten_id'];
				$time_ago = ago($result['timestamp']);
				$query_test = mysql_query("SELECT * FROM klanten WHERE klanten_id='{$klanten_id}'") or die(mysql_error());
				while($result_test = mysql_fetch_array($query_test)) {
					$naam = stripslashes($result_test['naam']);
					$bestelling_id = $result['bestelling_id'];
					echo '<li><a href="javascript:window.location=\'../bestelling/?id='.$bestelling_id.'\'" ><p>'.$naam.'</p><span>'.$time_ago.'</span></a></li>';
				}
			}
		?>
	</ul>
</body>
</html>