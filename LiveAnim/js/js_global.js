var $j = jQuery.noConflict();
var nb_erreur = 0;

// Fonctions de vérifications des formulaires.
function fx_verif_champ_vide(champ){
	if($j('#'+champ).val() == ""){
		return true;
	}else{
		return false;
	}
}

// Fonction de vérification basique (champ non vide).
function fx_verif_champ_simple(div, champ){
	champ_vide = fx_verif_champ_vide(champ);
	
	if(champ_vide){
		nb_erreur = 1;
	}else{
		nb_erreur = 0;
	}
	
	if(nb_erreur == 0){
		$j('#'+div).css({'width' : '100px', 'height' : '0px'});
		$j('#'+div).html("");
	}else{
		$j('#'+div).css({'width' : '100px', 'height' : '20px', 'padding-left' : '8px'});
		$j('#'+div).html("<span class='alert'>Champ vide</span>");
	}
	
	return champ_vide;
}

// Fonction de vérification entre deux champs devant avoir la même valeur.
function fx_verif_champ_double(div, champ1, champ2){
	nb_erreur = 0;
	var champ1_vide = fx_verif_champ_simple(div, champ1);
	var champ2_vide = fx_verif_champ_simple(div, champ2);
	
	if($j('#'+champ1).val() != $j('#'+champ2).val()){
		$j('#'+div).css({'width' : '400px', 'height' : '20px', 'padding-left' : '8px'});
		$j('#'+div).html("<span class='alert'>Les deux champs ne sont pas identiques.</span>");
		nb_erreur++;
	}
		
	if(nb_erreur == 0){
		$j('#'+div).css({'width' : '100px', 'height' : '0px'});
		return true;
	}else{
		return false;
	}
}

// Fonction de vérification d'un email.
function fx_verif_champ_email(div, champ){
	nb_erreur = 0;		
	var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');

	var email_valide = reg.test($j('#'+champ).val());
	
	if(!email_valide){
		$j('#'+div).css({'width' : '100px', 'height' : '20px', 'padding-left' : '8px'});
		$j('#'+div).html("<span class='alert'>Email&nbsp;incorrect&nbsp;!</span>");
		nb_erreur++;
	}
	
	if(nb_erreur == 0){
		$j('#'+div).css({'width' : '100px', 'height' : '0px'});
		$j('#'+div).html("");
		return true;
	}else{
		return false;
	}
}

// Fonction de vérification du numéro de SIRET.
function fx_verif_champ_siret(div, champ){
	nb_erreur = 0;		
	
	// On effectue la vérification que si le champ est non vide.
	var siret_vide = fx_verif_champ_vide(champ);

	if(!siret_vide){
		var reg = new RegExp('^[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{5}$', 'g');

		var siret_valide = reg.test($j('#'+champ).val());
		
		if(!siret_valide){
			$j('#'+div).css({'width' : '100px', 'height' : '20px', 'padding-left' : '8px'});
			$j('#'+div).html("<span class='alert'>Numéro&nbsp;de&nbsp;siret&nbsp;incorrect&nbsp;!&nbsp;<span class='noir petit'>(14&nbsp;chiffres:&nbsp;xxx&nbsp;xxx&nbsp;xxx&nbsp;xxxxx)</span></span>");
			nb_erreur++;
		}
		
		if(nb_erreur == 0){
			$j('#'+div).css({'width' : '100px', 'height' : '0px'});
			$j('#'+div).html("");
			return true;
		}else{
			return false;
		}
	}else{
		$j('#'+div).css({'width' : '100px', 'height' : '0px'});
		$j('#'+div).html("");
		return -1;
	}
}

// Fonctions de vérification des dates.
function fx_verif_champ_date(div, champ, typedate){
	nb_erreur = 0;	
	var date = $j('#'+champ).val();
	// On supprime les '/' en '-' s'il y en a.
	var regex_slash = /\//g;
	date = date.replace(regex_slash, "-")
	console.log(date);
	// 0: Date au format JJ/MM/AAAA
	// 1: Date au format JJ/MM/AAAA H:mn
	if(typedate == 0){
		var date_correcte = fx_verif_date_simple(div, date);

		if(date_correcte === true){
			$j('#'+div).html("");
			nb_erreur = 0;
			return true;
		}else if(date_correcte === 1){
			$j('#'+div).html("<span class='alert'>La date ne dois pas dépasser 10 caractère.</span>");
			nb_erreur = 1;
			return false;
		}else if(date_correcte === 2){
			$j('#'+div).html("<span class='alert'>La date ne dois pas contenir d'espace.</span>");
			nb_erreur = 1;
			return false;
		}else if(date_correcte === 3){
			$j('#'+div).html("<span class='alert'>Date au format JJ-MM-AAA ou JJ/MM/AAAA.</span>");
			nb_erreur = 1;
			return false;
		}else if(date_correcte === 4){
			$j('#'+div).html("<span class='alert'>Date minimale: 1980 | Date maximale: 2020.</span>");
			nb_erreur = 1;
			return false;
		}
	}else if(typedate == 1){

		if(date != ""){

			tab_date = date.split(' ');
			date_jmh = tab_date[0];
			
			var date_correcte = fx_verif_date_simple(div, date_jmh);

			if(date_correcte === true){
				$j('#'+div).html("");
				nb_erreur = 0;
			}else if(date_correcte === 1){
				$j('#'+div).html("<span class='alert'>La date ne dois pas dépasser 10 caractère.</span>");
				nb_erreur = 1;
				return false;
			}else if(date_correcte === 2){
				$j('#'+div).html("<span class='alert'>La date ne dois pas contenir d'espace.</span>");
				nb_erreur = 1;
				return false;
			}else if(date_correcte === 3){
				$j('#'+div).html("<span class='alert'>Date au format JJ-MM-AAA ou JJ/MM/AAAA.</span>");
				nb_erreur = 1;
				return false;
			}else if(date_correcte === 4){
				$j('#'+div).html("<span class='alert'>Date minimale: 1980 | Date maximale: 2020.</span>");
				nb_erreur = 1;
				return false;
			}
			
			date_hm = tab_date[1];
			regex_hm = new RegExp("^[0-9]{2}[h]{1}[0-9]{2}$","i")
			var hm_ok = regex_hm.test(date_hm);
			
			if(hm_ok){
				$j('#'+div).html("");
				nb_erreur = 0;
				return true;
			}else{
				$j('#'+div).html("<span class='alert'>L'heure est incorrecte. <span class='noir petit'>(Format 05h30)</span></span>");
				nb_erreur = 1;
				return false;
			}
			
		}else{
			$j('#'+div).html("<span class='alert'>Champ vide !</span>");
			nb_erreur = 1;
			return false;
		}
	}
}

function fx_verif_date_simple(div, valeur_date){
	var tabDate = valeur_date.split("-");
	tabDate = ConvNum(tabDate);
	var datTest_Date = new Date(parseInt(tabDate[2]), parseInt(tabDate[1])-1, parseInt(tabDate[0]));
	
	if(valeur_date.length>10){ 
		return 1;
	}
	
	for(i=0; i<valeur_date.length; i++){
		if (valeur_date.charAt(i) == ' '){ 
			return 2;
		}
	}
	
	if (valeur_date.length > 0){
		if ((parseInt(tabDate[0]) != datTest_Date.getDate()) || (parseInt(tabDate[1]) != parseInt(datTest_Date.getMonth())+1)){ 
			return 3;
		}
		
		if ((tabDate[2].length != 4) || (parseInt(tabDate[2]) < 1980) || (parseInt(tabDate[2]) > 2020)){ 
			return 4;
		}
	}
	return true;
}

function fx_verif_date_superieure(div, date1, date2, typedate){
	nb_erreur = 0;
	var date1_ok = fx_verif_champ_date(div, date1, typedate);
	var date2_ok = fx_verif_champ_date(div, date2, typedate);

	if(date1_ok == true && date2_ok == true){
		var result = compare_dates($j('#'+date1).val(), $j('#'+date2).val());

		if(result == 1){
			// Correctes
			$j('#'+div).html("");
			nb_erreur = 0;
			return true;
		}else if(result == 0){
			// Egales
			$j('#'+div).html("<span class='valide'>Votre recherche ne portera que sur un seul jour. <span class='petit noir'>Date de début et date de fin égales.</span></span>");
			nb_erreur = 0;
			return true;
		}else if(result == -1){
			// Incorrectes
			$j('#'+div).html("<span class='alert'>La date de début est supérieure à la date de fin !</span>");
			nb_erreur = 1;
			return false;
		}else{
			// Inconnues
			$j('#'+div).html("<span class='alert'>La date n'est pas au format attendu. <span class='noir petit'>(JJ-MM-AAAA ou JJ/MM/AAAA)</span></span>");
			nb_erreur = 1;
			return false;
		}
	}
}

// Enleve le '0' des nombres < 10
function ConvNum(tabDeDate) {
for (i=0; i<tabDeDate.length; i++)
tabDeDate[i] = (tabDeDate[i].charAt(0)=='0')?tabDeDate[i].charAt(1):tabDeDate[i];
return tabDeDate;
}

// Retourne true si valeur_date est postérieure à la date du jour
function date_future(valeur_date){
	var tabDate = valeur_date.split("-");
	var datAujourdhui = new Date();
	tabDate = ConvNum(tabDate);
	if (valeur_date.length > 0){ 
		var datTest_Date = new Date(parseInt(tabDate[2]), parseInt(tabDate[1])-1, parseInt(tabDate[0]));
		if (datTest_Date <= datAujourdhui){ 
			return false;
		}
	}
	return true;
}

// Retourne 1 si valeur_date1 < valeur_date2
// 0 si valeur_date1 = valeur_date2
// -1 si valeur_date1 > valeur_date2
function compare_dates(valeur_date1, valeur_date2){
	var tabDate1 = valeur_date1.split("-");
	tabDate1 = ConvNum(tabDate1);
	var datTest_Date1 = new Date(parseInt(tabDate1[2]), parseInt(tabDate1[1])-1, parseInt(tabDate1[0]));
	var tabDate2 = valeur_date2.split("-");
	tabDate2 = ConvNum(tabDate2);
	var datTest_Date2 = new Date(parseInt(tabDate2[2]), parseInt(tabDate2[1])-1, parseInt(tabDate2[0]));
	return (datTest_Date2-datTest_Date1==0)?"0":(datTest_Date2-datTest_Date1<0)?"-1":"1";
}


// --------------------------------- Fonctions de gestion de l'affichage et des évènements globaux -------------------------------------
function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '' : '<br />';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function fx_previsualiser(id_texte, id_zone_affichage){
	document.getElementById(id_zone_affichage).innerHTML = nl2br(document.getElementById(id_texte).value, false);
	new ElementMaxHeight();
}

var div_affiche = 0;
var plus= "images/Petit_plus.png";
var moins= "images/Petit_moins.png";

function initialiser_div(div){
	document.getElementById(div).style.display = 'none';
}

function fx_affiche(div, img){
	div = document.getElementById(div);
	img = document.getElementById(img);
	if(div_affiche == 0){
		div_affiche = 1;
		div.style.display = 'inline';
		img.src = moins;
		new ElementMaxHeight();
	}else{
		div_affiche = 0;
		div.style.display = 'none';
		img.src = plus;
		new ElementMaxHeight();
	}

}

// Fonction qui modifie un attribut css d'un élément. (Sert surtout pour m'en souvenir et que ça rentre xD)
function fx_modif_css(div, attr, value){
	$j('#'+div).css(attr, value);
	new ElementMaxHeight();// On recharge la hauteur de la page.
}