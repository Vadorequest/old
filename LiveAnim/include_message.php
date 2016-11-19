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
	if($id_message_ok){
?>
	<h2><?php echo $message[0]['TITRE'] ?></h2><br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique"><?php if($message[0]['STATUT_MESSAGE'] == "Non lu"){echo "Nouveau message:";}else{echo "Message:";} ?></legend>
		<br />
		<b class='valide'>Date de réception:</b> <?php echo $message[0]['DATE_ENVOI'] ?><br />
		<br />
		<b class='valide'>Date de lecture:</b> <?php echo $message[0]['DATE_LECTURE'] ?><br />
		<br />
		<b class='valide'>Expéditeur:</b> <?php if($message[0]['EXPEDITEUR'] != 0){ ?><a href="<?php echo $oCL_page->getPage('')."?id_personne=".$message[0]['EXPEDITEUR']; ?>"><?php echo $expediteur[0]['PSEUDO'] ?></a><?php }else{echo "<b class='rose'>LiveAnim</b>";} ?>
	<br />
	<br />
	<fieldset class="padding_LR">
	<center><b><u class='valide'>Corps du message:</u></b><br /></center><br />
	<br />
	<?php echo $message[0]['CONTENU'] ?><br />
	<br />
	</fieldset>
	<br />
	<center>
		<a href="<?php echo $oCL_page->getPage('messagerie'); ?>">Retour à ma messagerie</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $oCL_page->getPage('supprimer_message')."?ids_msg=".$message[0]['ID_MESSAGE']; ?>"><img src="<?php echo $oCL_page->getImage('petite_croix'); ?>" alt="Supprimer le message" title="Supprimer le message" /></a>
	</center>
	</fieldset>

<?php
	}else{
		if($_SESSION['message']['message_affiche'] == false){
			echo $_SESSION['message']['message'];
			$_SESSION['message']['message_affiche'] = true;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>