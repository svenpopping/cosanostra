<?php
	include('../../config.php');
	
	session_start();
	$user = $_SESSION['username'];
	$pass = $_SESSION['sha1_password'];
	if (!isset($user) || !isset($pass)) {
	die(header( "Location: ../"));
	}
	//check that the form fields are not empty, and redirect back to the login page if they are
	elseif (empty($user) || empty($pass)) {
	die(header("Location: ../"));
	}
	else{
		$result = mysql_query("SELECT * FROM admin WHERE user='{$user}' AND pass='{$pass}'") or die(mysql_error());

		$rowCheck = mysql_num_rows($result);
		if($rowCheck <= 0){
			die(header("Location: ../"));
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Cosa Nostra Admin - Bestellingen</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../../css/admin.css" media="all" />
	</head>
	<script src="../../js/jquery.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	var timeout = false;
	function load_items() {if(timeout != true) {		
			var urlKoeriers='../json/bestellingen/';
			$.getJSON(urlKoeriers + '?' + Math.round(new Date().getTime()), function(jsonKoeriers) {
				var list = '';
				
				if(jsonKoeriers.bestellingen[0] != null) {
					$.each(jsonKoeriers.bestellingen,function(i,item){
						if(item.status == 1) { list += '<li id="item_'+item.bestelling_id+'" class="new">\n' }
						else { list += '<li id="'+item.bestelling_id+'">\n' };
						list += '<div class="options">\n';
						if(item.status == 1) {
							list += '<a href="javascript:voltooien(\''+item.bestelling_id+'\');" class="btn-voltooien">Bestelling Voltooien</a>'};
						if(item.status == 2) {
							list += '<a class="btn-bezorgen">Bezorging: '+item.koerier_naam+'</a>'};
						list += '<span class="timeago">'+item.timeago+'</span>\n';
						list += '</div>\n';
						list += '<p>\n';
						$.each(item.bestelling,function(i,pizza){
							list += pizza.pizza_naam;
							if(pizza.opmerkingen) {
							list += ' <span class="opmerking">('+unescape(pizza.opmerkingen)+')</span>'};
							list += ', ';
						});
						list += '</p>\n';
						if(item.status == 1) {
							list += '<div class="tooltip" id="tooltip_'+item.bestelling_id+'"><p>Selecteer een beschikbare koerier om de bestelling te voltooien door deze te laten bezorgen.</p>';
							list += '<ul id="bezorgers_'+item.bestelling_id+'"class="bezorgers">\n';
							list += '</ul></div>\n';
						}
						//list += '<div class="tooltip"></div>\n';
					});
				
				
		$("#bestellingen").html(list);
		}
		else {
			$("#bestellingen").html('<li><p>Nog geen geplaatste bestellingen.</p></li>');
		}
	});
	setTimeout("load_items();", 5000)};
	}
	
	function load_koeriers(bestelling_id) {
		var urlKoeriers='../json/koeriers/';
		$.getJSON(urlKoeriers,function(jsonKoeriers){
			var koeriers = '';
			$.each(jsonKoeriers.koeriers,function(i,koerier){
			
			if(koerier.status != 3) {
				koeriers += '<li><a class="koerier" href="javascript:koerier(\''+bestelling_id+'\',\''+koerier.koerier_id+'\');">'+koerier.naam+'<span class="status '+koerier.status_text+'">'+koerier.status_text+'</span></a></li>\n';
			} else {
				koeriers += '<li><div class="koerier">'+koerier.naam+'<span class="status '+koerier.status_text+'">'+koerier.status_text+'</span></div></li>\n';
			}
			
			});	
			$('#bezorgers_'+bestelling_id).html(koeriers);		
		});	
	}
	
	var popup_show = false;
	function voltooien(bestelling_id) {	
		if(popup_show != false) {
			$("#tooltip_"+bestelling_id).fadeOut('slow');
			start_time();
			popup_show = false;
		} else {
			stop_time();
			load_koeriers(bestelling_id);
			$("#tooltip_"+bestelling_id).fadeIn('slow');
			popup_show = true;
		}
		
	}
	function stop_time() {
		timeout = true;
	}
	function start_time() {
		timeout = false;
		load_items();
	}
	
	function koerier(bestelling_id,koerier_id) {
		$.get("set_delivery/", {"bestelling":bestelling_id, "koerier":koerier_id});
		$("#tooltip_"+bestelling_id).fadeOut('slow');
		start_time();
		popup_show = false;
	}
	
	</script>
</head>
<body onload="load_items();">
<span class="content">
	<div id="header">
		<p>Hallo <?php echo $user; ?>, <a href="../sessions/destroy/">Log Uit</a></p><div class="clear"></div>
		<h2>Cosa Nostra Admin</h2>
		<ul id="nav">
			<li><a href="#" class="nav-bestellingen">Bestellingen</a></li>
			<li><a href="../klanten/" class="nav-klanten">Klanten</a></li>
			<li><a href="../archief/" class="nav-archief">Archief</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<h1>Bestellingen</h1>
	<div id="informatie">
		<span>Bestellingen worden automatisch vernieuwd, <span class="icon-small" style="background-position: -112px 0;"></span></span><a class="btn" href="javascript:load_items();">Vernieuw bestellingen handmatig</a>
	</div>
	<div class="clear"></div>
		<ul id="bestellingen">
			<li>
				<p>Nog geen geplaatste bestellingen.</p>
			</li>
		</ul>
		<div id="footer">Praktische Opdracht Informatica - Sietse de Boer, Bart Falkena, Rene Hiemstra, Sven Popping, Bouke Regnerus</div>
</span>
</body>
</html>