<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
		<img style="" alt="Supprimer mon compte"
 src="images/supprimer-compte.png">
<br />
<br />
	
	<?php
	if(isset($_SESSION['supprimer_compte']['message']) && $_SESSION['supprimer_compte']['message_affiche'] == false){
		echo $_SESSION['supprimer_compte']['message']."<br /><br />";
		$_SESSION['supprimer_compte']['message_affiche'] = true;
	}	
	
	?>
	
	Voici le formulaire de suppression du compte.<br />
	Afin de satisfaire toujours au mieux notre clientèle nous vous demandons d'indiquer la raison de la suppression.<br />
	Cette information sera étudiée afin de savoir si des améliorations sont nécessaires et envisageables.<br />
	<br />
	À noter que votre compte ne sera pas immédiatement supprimé. Nous conservons l'intégralité de vos informations pour une durée de deux mois.<br />
	Vos informations -quelles qu'elles soient- ne seront pas divulguées à des tiers ni utilisées autrement que dans un but d'amélioration de nos services et de statistiques.<br />
	<br />
	Si vous souhaitez toutefois supprimer définitivement vos informations <u title='Seules les informations suivantes seront immédiatement supprimées. Nom, prénom, date de naissance, photo, civilité, email, ville, adresse, code postal, téléphones.'>personnelles</u> vous pouvez cocher la case correspondante et elles seront supprimées immédiatement.<br />
	<br />
	<br />
	<fieldset><legend class="legend_basique">Suppression du compte:</legend><br />
		<br />
		<form class="formulaire" method="post" action="script_supprimer_compte.php" name="form_supprimer_compte" id="form_supprimer_compte" >
			Entrez votre adresse e-mail:<br />
			<input onblur="fx_verif_champ_email('email', 'form_supprimer_compte_email');" type="text" name="form_supprimer_compte_email" id="form_supprimer_compte_email" /><br />
			<div id="email"></div>
			<br />
			Expliquez les raisons qui vous poussent à supprimer votre compte:<br />
			<textarea onblur="fx_verif_champ_simple('raison', 'form_supprimer_compte_raison');" name="form_supprimer_compte_raison" id="form_supprimer_compte_raison" cols="90" rows="10" ></textarea><br />
			<div id="raison"></div>
			<br />
			Entrez votre mot de passe:<br />
			<input onblur="fx_verif_champ_simple('mdp', 'form_supprimer_compte_mdp');" type="password" name="form_supprimer_compte_mdp" id="form_supprimer_compte_mdp" /><br />
			<div id="mdp"></div>
			<br />
			Je souhaite supprimer immédiatement mes informations <u title='Seules les informations suivantes seront immédiatement supprimées. Nom, prénom, date de naissance, photo, civilité, email, ville, adresse, code postal, téléphones.'>personnelles</u>.&nbsp;<select name="form_supprimer_compte_infos_perso" id="form_supprimer_compte_infos_perso"><option value="0" selected="selected">Non</option><option value="1">Oui</select><br />
			<br />
			<span class="fright alert">Tous les champs sont obligatoires.&nbsp;&nbsp;</span><br />
			<br />
			<center>
				<input type="image" src="images/valider.png" alt="Supprimer mon compte" title="Supprimer mon compte" name="btn_form_supprimer_compte_valider" id="btn_form_supprimer_compte_valider" />
			</center>
		</form>
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>