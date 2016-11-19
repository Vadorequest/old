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
	<img style="" alt="Envoyer une invitation"
 src="images/envoyer-invitations.png">
<br />
<br />
	
	
	<?php
	if(isset($_SESSION['envoyer_invitations']['message']) && $_SESSION['envoyer_invitations']['message_affiche'] == false){
		echo $_SESSION['envoyer_invitations']['message'];
		$_SESSION['envoyer_invitations']['message_affiche'] = true;
	}
	?>
	<br />
	<form class="formulaire" name="form_envoyer_invitations" method="post" action="script_envoyer_invitations.php">
		<label for="form_envoyer_invitations_emails">E-mails de vos amis:</label><br />
		<textarea onblur="fx_verif_champ_simple('emails', 'form_envoyer_invitations_emails');" rows="5" cols="80" name="form_envoyer_invitations_emails" id="form_envoyer_invitations_emails" required></textarea><br />
		<span class="petit noir"> Séparez les adresses par des points-virgule (;)</span>
		<div id="emails"></div><br />
		<center>
			<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" title="Envoyer l'invitation" />
		</center>		
	</form>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>