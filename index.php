<?php
	include('config.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /> <!-- Content type. -->
	<title>Cosa Nostra - Pizzeria Restaurant</title> <!-- Titel van de pagina. -->
	<link rel="stylesheet" type="text/css" href="css/style.css" media="all" /> <!-- Laad de basis stylesheet. -->
	<link rel="stylesheet" type="text/css" href="css/mainpage.css" media="all" /> <!-- Laad de mainpage stylesheet. -->
	
	<link rel="apple-touch-icon-precomposed" href="images/mobile/apple-touch-icon.png"/> <!-- Mobiel icoon voor de iPhone en iPod touch. -->
	
	<script type="text/javascript" src="js/json2.js"></script> <!-- Laad de JSON library (http://json.org/js.html). -->
	<script src="js/smoothscroll.js" type="text/javascript"></script> <!-- Laad de smooth scroll library (http://www.kryogenix.org/code/browser/smoothscroll/). -->
	<script src="js/jquery.js" type="text/javascript"></script> <!-- Laad de jQuery library (http://jquery.com/). -->
	
	<script type="text/javascript">
		      /* Verberg alle items als de webpagina is geladen. */
		      $(document).ready(function() { 
		    	$('#login').hide(); 
		    	$('a#btn-terug').hide();
		    	$('#registreer').hide();
		    	$('a#btn-informatie').hide();
		    
		    	/* Bij het klikken op de login button moet het login item worden geladen. */
		    	$('a#btn-login').click(function() {
		    		$('#login').fadeIn('slow');
		    		$('#bestelling').hide();
		    		$('a#btn-terug').fadeIn('slow');
		    		$('a#btn-login').hide();
		    	return false;
		    	});
		    	
		    	/* Bij het klikken op de terug button moet het bestelformulier weer worden geladen. */
		    	$('a#btn-terug').click(function() {
		    		$('#bestelling').fadeIn('slow');
		    		$('#login').hide();
		    		$('a#btn-login').fadeIn('slow');
		    		$('a#btn-terug').hide();
		    	return false;
		    	});
		    	
		    	/* Bij het klikken op de registreer button moet het regitreerformuier worden geladen. */
		    	$('#btn-registreer').click(function() {
		    		$('#registreer').fadeIn('slow');
		    		$('#informatie').hide();
		    		$('a#btn-informatie').fadeIn('slow');
		    		$('a#btn-registreer').hide();
		    	return false;
		    	});
		    	
		    	/* Bij het klikken op de registreer button moet het regitreerformuier worden geladen. */
		    	$('#btn-registreer-info').click(function() {
		    		$('#registreer').fadeIn('slow');
		    		$('#informatie').hide();
		    		$('a#btn-informatie').fadeIn('slow');
		    		$('a#btn-registreer').hide();
		    	return false;
		    	});
		    	
		    	/* Bij het klikken op de informatie button de informatie over de klantenkaart weer worden geladen. */
		    	$('a#btn-informatie').click(function() {
		    		$('#informatie').fadeIn('slow');
		    		$('#registreer').hide();
		    		$('a#btn-registreer').fadeIn('slow');
		    		$('a#btn-informatie').hide();
		    	return false;
		    	});
		
				/*  Labels met informatie moeten verdwijnen wanneer de gebruiker zijn informatie wachtwoord wil intypen */
		    	$('#login-pass-clear').show();
		    	$('#login-pass').hide();
		     
		    	$('#login-pass-clear').focus(function() {
		    		$('#login-pass-clear').hide();
		    		$('#login-pass').show();
		    		$('#login-pass').focus();
		    	});
		    	$('#login-pass').blur(function() {
		    		if($('#login-pass').val() == '') {
		    			$('#login-pass-clear').show();
		    			$('#login-pass').hide();
		    		}
		    	});
		    	
		    	$('#registreer-pass-clear').show();
		    		$('#registreer-pass').hide();
		    	
		    		$('#registreer-pass-clear').focus(function() {
		    			$('#registreer-pass-clear').hide();
		    			$('#registreer-pass').show();
		    			$('#registreer-pass').focus();
		    		});
		    		$('#registreer-pass').blur(function() {
		    			if($('#registreer-pass').val() == '') {
		    				$('#registreer-pass-clear').show();
		    				$('#registreer-pass').hide();
		    			}
		    		});
		    		
		    	$('#registreer-validation-clear').show();
		    		$('#registreer-validation').hide();
		    	
		    		$('#registreer-validation-clear').focus(function() {
		    			$('#registreer-validation-clear').hide();
		    			$('#registreer-validation').show();
		    			$('#registreer-validation').focus();
		    		});
		    		$('#registreer-validation').blur(function() {
		    			if($('#registreer-validation').val() == '') {
		    				$('#registreer-validation-clear').show();
		    				$('#registreer-validation').hide();
		    			}
		    		});
		     
		    	$('.input').each(function() {
		    		var default_value = this.value;
		    		$(this).focus(function() {
		    			if(this.value == default_value) {
		    				this.value = '';
		    			}
		    		});
		    		$(this).blur(function() {
		    			if(this.value == '') {
		    				this.value = default_value;
		    			}
		    		});
		    	});
		    	
		    	$('.opmerkingen').each(function() {
		    		var default_value = this.value;
		    		$(this).focus(function() {
		    			if(this.value == default_value) {
		    				this.value = '';
		    			}
		    		});
		    		$(this).blur(function() {
		    			if(this.value == '') {
		    				this.value = default_value;
		    			}
		    		});
		    	});
		     
		    });
		    /* Einde labels. */
		    
		    /*Registreer input moet automatisch streepjes toevoegen volgens de door ons bepaalde bank-nr code. */
		    function registreerNummer() {
		    nummer = $('#registreer-nummer').val();
		    
		    for(i=0; i<nummer.length; i++) {
		    	if((nummer.length == 3) || (nummer.length == 11)){
		    	$('#registreer-nummer').val(nummer + "-");
		    	}
		    	if(nummer.length >= 15) {
		    	$('#registreer-nummer').val(nummer.substring(0,nummer.length-1));
		    	}
		    }		
		    }
		    
		    /* Bestelling */
		    var json = {'bestelling': []}; /* JSON array moet worden aangemaakt voor de bestelling. */
		    function updateJSON()
		    {
		    	var string = JSON.stringify(json); /* String moet worden omgezet naar een JSON. */
		    	document.getElementById('json').value = encodeURIComponent(string); /* "lege" JSON array wordt in de div gezet. */
		    }
		    /* Toevoegen van pizzas */
		    function addElement(id, pizza, prijs) {
		    	var hiddenIndex = document.getElementById('hiddenIndex'); /* hiddenIndex telt hoeveel pizzas er in de bestelling staan. */
		    	var nummer = (document.getElementById('hiddenIndex').value -1)+ 2; /* maak een nummer van de hidden index. */
		    	hiddenIndex.value = nummer;
		    	var idNaam = "element_"+nummer;
		    	var i = nummer -1;
		    	var oNewli = document.createElement("li"); /* Nieuw list-item wordt aangemaakt. */
		    	var oNewa=document.createElement("a"); /* Link wordt aangemaakt in het nieuwe list-item. */
		    		oNewa.setAttribute("href","javascript:removeElement('" + idNaam + "','" + prijs + "','" + i + "')"); /* Een link atribuut wordt toegevoegd aan de link. */
		    		oNewtext=document.createTextNode(pizza); /* Text wordt toegevoegd aan de link. */
		    		oNewa.appendChild(oNewtext);
		    	oNewli.setAttribute('id',idNaam); /* Een id atribuut wordt toegevoegd aan het nieuwe list-item. */
		    	oNewli.appendChild(oNewa); /* De aangemaakte link wordt in het list-item gezet */
		    	document.getElementById('bestellingList').appendChild(oNewli); /* Het list-item inclusief link wordt aan de bestellinglijst toegevoegd. */
		    	var opmerkingen = document.getElementById(id).value; /* Eventuele opmerkingen worden geladen uit de website.  */
		    	if (opmerkingen == 'Opmerkingen') {
		    		opmerkingen = ''; /* Als er geen opmerkingen zijn toegevoegd wordt er een lege opmerking aangemaakt. */
		    	} else {
		    		document.getElementById(id).value = 'Opmerkingen';
		    	}
		    	json.bestelling.push({"pizza_naam": pizza, "opmerkingen": escape(opmerkingen)}); /* De pizza informatie en opmerkingen worden toegevoegd aan de JSON array. */
		    	updateJSON(); /* De  JSON array wordt geupdate me de nieuwe toegevoegde informatie. */
		    	
		    	huidigePrijs = document.getElementById('price').innerHTML; /* Haal de huidige prijs uit de website */
		    	totaalPrijs = Math.round((parseFloat(prijs) + parseFloat(huidigePrijs))*100)/100; /* Update de huidige prijs naar de nieuwe totaal prijs. */
		    	document.getElementById('price').innerHTML = totaalPrijs;
		    	document.getElementById('price-hidden').value = totaalPrijs;	
		    } 
		    /* Verwijderen van pizzas */
		    function removeElement(idNaam,prijs,i) {
				document.getElementById("bestellingList").removeChild(document.getElementById(idNaam)); /* Haal het list-item uit de lijst. */
		    	delete json.bestelling[i]; /* delete het JSON item uit de JSON array. */
		    	updateJSON();
		
				huidigePrijs = document.getElementById('price').innerHTML; /* Haal de huidige prijs van de website. */
				floatPrijs = parseFloat(huidigePrijs) - parseFloat(prijs); /* Haal de prijs van de pizza af van de totaal prijs. */
				totaalPrijs = Math.round(floatPrijs*100)/100;
				document.getElementById('price').innerHTML = totaalPrijs; /* Zet de nieuwe prijs weer op de website. */
				document.getElementById('price-hidden').value = totaalPrijs;
		    }
	</script>
	</head>
<body>
	<!-- Hoofd Pagina -->
	<div id="mainpage">
		<span class="content">
			<div class="logo">Cosa Nostra</div>
			<div class="slogan">Cosa Nostra pizzeria restaurant, de lekkerste pizza’s in Drachten. Restaurant, afhaal &amp; online bestel service met  gratis bezorging!</div>
			<a href="#bestelonline" class="btn-bestelonline" title="Beste Online">Bestel Online</a>
			<div class="nav">
				<a href="#contact" title="Contact/Adres Gegevens">Contact/Adres Gegevens</a>
				<a href="admin/" title="Admin Pagina">Admin</a>
			</div>
		</span>
		<span class="pagecut-bottom"></span>
	</div>
	
	<!-- Bestel Online -->
	<div id="bestelonline">
		<span class="content">
			<h1>Bestel Online</h1>
			<h3>Selecteer de lekkerste pizza’s van onze menu kaart, vul uw gegevens in en uw bestelling wordt gratis bij u bezorgd!</h3>
		</span>
		<div id="menukaart">
			<span class="pagecut-top"></span>
			<span class="content">
				<h2>Online Menu Kaart</h2>
				<h3><span class="icon-small" style="background-position: 0 0;"></span>Maak een selectie uit een of meerdere van onze pizza’s.</h3>
				<p><span class="icon-small" style="background-position: -16px 0;"></span>Al onze pizza’s worden bereid met de beste en meest verse ingrediënten.</p>
				<div class="pizza-items">
					<ul>
						<?php
						$i = 0;
						$query_pizza = mysql_query("SELECT * FROM pizza") or die(mysql_error()); /* Selecteer alle pizzas. */
						
						/* Laad alle pizzas uit de database in een lijst. */
						while($result_pizza = mysql_fetch_array($query_pizza)) {
						?>
						<li>
							<a href="javascript:addElement('pizza_<?php echo $result_pizza['pizza_id'];?>', '<?php echo $result_pizza['pizza_naam'];?>', '<?php echo $result_pizza['prijs'];?>')" class="img-pizza" style="background-position: 0 -<?php echo $i;?>px;"><?php echo $result_pizza['pizza_naam'];?></a>
							<h3><?php echo $result_pizza['pizza_naam'];?></h3>
							<p><?php echo $result_pizza['omschrijving'];?></p>
							<span class="prijskaart">€ <?php echo $result_pizza['prijs'];?></span>
							<a href="javascript:addElement('pizza_<?php echo $result_pizza['pizza_id'];?>', '<?php echo $result_pizza['pizza_naam'];?>', '<?php echo $result_pizza['prijs'];?>')" class="btn-toevoegen">Pizza Toevoegen</a>
							<input type="text" name="pizza_<?php echo $result_pizza['pizza_id'];?>" id="pizza_<?php echo $result_pizza['pizza_id'];?>" value="Opmerkingen" class="opmerkingen" />
						</li>
							<?php
							$i = $i + 96; /* Tel 96px op bij i, 96px is de hoogte/breedte van een pizza afbeelding, zo wordt het volgende plaatje uit de pizza sprite geladen.*/
						}
						?>	
					</ul>
					<div class="clear"></div>
				</div>
				
				<div class="geselecteerd">
					<input type="hidden" value="0" id="hiddenIndex" />
					<span class="title"><span class="icon-small" style="background-position: -64px 0;"></span>Geselecteerde Pizza's:</span>
					<ul id="bestellingList">
					</ul>
					<span class="totaalprijs"><span class="icon-small" style="background-position: -48px 0;"></span>Totaal Prijs: € <span id="price">0</span></span>
					<div class="clear"></div>
					
				</div>
			</span>
		<span class="pagecut-bottom"></span></div>	
		<span class="content">
			<div class="login-terugkerendeklant" id="terugkerendeklant"> 
				<a href="#" class="btn-gray" id="btn-login">Terugkerende klant met klanten kaart</a>
				<a href="#" class="btn-gray" id="btn-terug">Terug naar bestelling</a>
				<p>of <a href="#" class="btn-klantenkaart-maken" id="btn-registreer">klantenkaart aanmaken.</a><a href="#" class="btn-informatie" id="btn-informatie">terug naar informatie.</a></p>
			</div>

			<form action="bestelling/" method="post"> 
				<input type="hidden" name="json-bestelling" id="json" value="" />
				<input type="hidden" name="prijs" id="price-hidden" value="" />
				<div id="bestelling">
				<p><input type="text" class="input" value="Voor en Achternaam" name="naam" id="bestelling-naam" /></p> 
				<p><input type="text" class="input straat" value="Straatnaam" name="straat" id="bestelling-adres"/></p> 
				<p><input type="text" class="input huisnr" value="Huisnr" name="huisnr" id="bestelling-adres" /></p> 
				<p><input type="text" class="input postcode" value="Postcode" name="postcode" id="bestelling-postcode"/>
				<input type="text" class="input woonplaats" value="Woonplaats" name="woonplaats" id="bestelling-woonplaats"/></p> 
				<p><input id="bestelling-btn" class="btn-bestelling" type="submit" value="Plaats Bestelling" /></p>
				</div>
				
				<div id="login"> 
				<p><input type="text" class="input" value="Emailadres" name="email" id="login-email"/></p> 
				<p><input type="text" class="password password-clear" value="Wachtwoord" name="pass" autocomplete="off" id="login-pass-clear" /><input type="password" class="password" value="" name="pass" id="login-pass" autocomplete="off" /></p> 
				<p><input id="bestelling-btn" class="btn-bestelling" type="submit" value="Plaats Bestelling" /></p>
				</div>
			</form>

			<div id="informatie">
				<div id="bezorging"> 
					<span class="icon-large" style="background-position: 0 -16px; margin: 3px 3px 0 0;"></span><h3>Bezorging</h3> 
					<p>Onze pizza's worden gratis door heel Smallingerland bezorgd. Nadat u bij ons een bestelling heeft geplaatst, wordt uw bestelling bereid door onze gespecialiseerde pizzabakkers. Uw bestelling zal daarna direct, door een van onze vijf koeriers, bezorgd worden. Wij garranderen dat uw de pizza ontvangt binnen een half uur.</p> 
				</div> 
				<div id="klantenkaart"> 
					<span class="icon-large" style="background-position: -24px -16px; margin: 3px 3px 0 0;"></span><h3>Klantenkaart</h3> 
					<p>Klanten met een klantenkaart kunnen direct betalen met hun bankgegevens, ze hoeven niet iedere keer hun adresgegevens op nieuw in te vullen. Ook ontvangen deze klanten iedere maand een gratis nieuwsbrief met het laatste nieuws en aanbiedingen.</p> <a href="#" class="btn-yellow" id="btn-registreer-info">Klantenkaart aanmaken</a> 
				</div> 
			</div>
			
			<form action="register/" method="post" id="registreer"> 
				<span class="icon-large" style="background-position: -96px -16px; margin: 3px 3px 0 0;"></span><h3>Registeer</h3>
				<p><input type="text" class="input" value="Voor en Achternaam" name="naam" id="registreer-naam"/></p> 
				<p><input type="text" class="input straat" value="Straatnaam" name="straat" id="registreer-adres"/></p> 
				<p><input type="text" class="input huisnr" value="Huisnr" name="huisnr" id="registreer-adres"/></p> 		
				<p><input type="text" class="input postcode" value="Postcode" name="postcode" id="registreer-postcode"/>
				<input type="text" class="input woonplaats" value="Woonplaats" name="woonplaats" id="registreer-woonplaats"/></p> 
				<p><input type="text" class="input" value="Rekeningnummer (xxx-xxxxxxx-xx)" name="registreer-nummer" onKeyDown="registreerNummer()" onKeyUp="registreerNummer()" id="registreer-nummer"/></p> 
				<p><input type="text" class="input" value="Emailadres" name="email" id="registreer-email"/></p> 
				<p><input type="text" class="password password-small password-clear" value="Wachtwoord" name="pass" autocomplete="off" id="registreer-pass-clear" /><input type="password" class="password password-small" value="" name="password" id="registreer-pass"/>
				<input type="text" class="password password-small password-repeat password-clear" value="Herhaal Wachtwoord" name="pass" autocomplete="off" id="registreer-validation-clear" /><input type="password" class="password password-repeat" value="" name="validation" id="registreer-validation"/></p> 
				<p><input id="registreer-btn" class="btn-registreer" type="submit" value="Registreer" /></p>
			</form>
			
            <div class="clear"></div>
		</span> 
	</div>
	
	<!-- Contact -->
	<div id="contact">
		<span class="shadow-top"></span>
		<span class="content">
			<div class="kaart"><a href="http://maps.google.nl/maps?f=q&source=s_q&hl=nl&geocode=&q=Torenstraat+28+9201+JW+Drachten+(Cosa+Nostra)&aq=&sll=53.110592,6.101747&sspn=0.007058,0.019269&ie=UTF8&hq=&hnear=Torenstraat+28,+Drachten,+Smallingerland,+Friesland&ll=53.110746,6.101747&spn=0.007058,0.019269&z=16&iwloc=A" target="_blank" border="0"><img src="images/contact/map.png" width="298" height="200" alt="Locatie Cosa Nostra" /></a>
			</div>
			
			<div id="adres"> 
				<span class="icon-large" style="background-position: -48px -16px;"></span><h3>Adres Gegevens</h3> 
				<p>Cosa Nostra</p> 
				<p>Torenstraat 28</p> 
				<p>9201 JW Drachten</p> 
				<p>Tel. (0512) 57 10 20</p> 
				<p>Email. <a href="mailto:info@cosanostra.nl" title="Stuur ons een email">info@cosanostra.nl</a></p> 
			</div> 
			
			<div id="vacatures"> 
				<span class="icon-large" style="background-position: -72px -16px;"></span><h3>Vacatures</h3> 
				<p>Wij zijn op zoek naar ervaren jongens en meisjes voor de bediening. Neem hiervoor contact met ons op of kom even langs.</p><br/>
				<p>BEZORGERS met brom(rijbewijs), leeftijd vanaf 16 jaar.
			</div>
			
			<div class="clear"></div>
			
			<span class="goodbye">Tot ziens bij Cosa Nostra!</p></span>
		</span>
		<span class="shadow-bottom"></span>
	</div> 
		<span class="content" id="close"><p>Praktische Opdracht Informatica - <a href="http://twitter.com/sietse_de_boer" title="Sietse de Boer op Twitter">Sietse de Boer</a>, Bart Falkena, Rene Hiemstra, <a href="http://twitter.com/svenpopping" title="Sven Popping op Twitter">Sven Popping</a>, <a href="http://twitter.com/bouke" title="Bouke Regnerus op Twitter">Bouke Regnerus</a></p></span>
	
	<!-- Footer -->	
	<div id="footer"> 
		<span class="pagecut-top"></span>
		<span class="content">
			<p>Webdesign door <a href="http://512.net76.net/">Sven Popping</a> en <a href="http://regner.us/blog/" title="Regner.us Blog">Bouke Regner.us</a> - Hosting door <a href="http://www.000webhost.com/418931.html">000webhost.com</a></p>
		</span>
	</div>
	
	<!-- Google Analytics Tracking Code -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17390636-4']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	
	<!-- Einde HTML -->
</body> 
</html>
