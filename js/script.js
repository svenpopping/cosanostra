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
