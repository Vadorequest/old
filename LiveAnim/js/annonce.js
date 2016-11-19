var $j = jQuery.noConflict();

function fx_previsualiser_annonce(){
	// On récupère les variables formulaire.
	var titre = $j('#form_ajout_modification_annonce_titre').val();
	var type_annonce = $j('#form_ajout_modification_annonce_type_annonce').val();
	var date_debut = $j('#form_ajout_modification_annonce_date_debut').val();
	var date_fin = $j('#form_ajout_modification_annonce_date_fin').val();
	var artistes_recherches = $j('#form_ajout_modification_annonce_artistes_recherches').val();
	var budget = $j('#form_ajout_modification_annonce_budget').val();
	var nb_convives = $j('#form_ajout_modification_annonce_nb_convives').val();
	var description = $j('#form_ajout_modification_annonce_description').val();
	var departement = $j('#form_ajout_modification_annonce_id_departement').val();
	var adresse = $j('#form_ajout_modification_annonce_adresse').val();
	var cp = $j('#form_ajout_modification_annonce_cp').val();
	var ville = $j('#form_ajout_modification_annonce_ville').val();
	
	// On met les infos dans les div.
	$j('#previsualiser_titre').html("<center>"+titre+"</center><br />");
	$j('#previsualiser_type_annonce').html("<center>Evénement: <span class='noir'>"+type_annonce+"</span></center><br />");
	$j('#previsualiser_dates').html("<center>La représentation débute le <b>"+date_debut+"</b> et se termine le <b>"+date_fin+"</b></center><br />");
	$j('#previsualiser_budget_nb_personne').html("<center>Le budget initial est de <b>"+budget+"€</b>.<br />La représentation se fera devant <b>"+nb_convives+" personnes</b>.</center><br />");
	$j('#previsualiser_description').html("<span class='rose'>Description:<br /></span>"+description+"<br /><br />");
	$j('#previsualiser_artistes_recherches').html("<span class='rose'>Artistes recherchés:<br /></span>"+artistes_recherches+"<br /><br />"+
		"<span class='petit'>L'adresse est utilisée uniquement afin de faciliter la géolocalisation.</span>");
	
	
	new ElementMaxHeight();
}