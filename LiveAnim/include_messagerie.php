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

	<img style="" alt="Messagerie"
 src="images/messagerie.png">
<br />
<br />
	
	<?php
	if(isset($_SESSION['messagerie']['message']) && $_SESSION['messagerie']['message_affiche'] == false){
		echo $_SESSION['messagerie']['message'];
		$_SESSION['messagerie']['message_affiche'] = true;
	}	
	?>
	<?php
		if($nb_result[0]['nb_message'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_message'], $page_actuelle);
		}
	?>
	<form name="supprimer_messages" action="script_supprimer_messages.php" method="post">
	<table width="100%">
		<tr class="formulaire">
			<th width="40%" scope="col">Titre</th>
			<th width="20%" scope="col">Expéditeur</th>
			<th width="20%" scope="col">Date de <br />réception</th>
			<th width="15%" scope="col">&Eacute;tat</th>
			<th width="5%"scope="col"><input type="checkbox" onclick="invertselection('ids_msg[]');" /></th>
		</tr>
		<?php
		if(count($messages) > 0){
			foreach($messages as $key=>$message){
			?>
			<tr><th colspan="5"><hr /></th></tr>
			<tr height="60px">
				<th title="Lire le message" scope="row"><a href="<?php echo $oCL_page->getPage('message')."?id_message=".$message['ID_MESSAGE']; ?>"><?php echo $message['TITRE']; ?></a></th>
				<th><?php if($message['EXPEDITEUR'] != 0){ echo $message['PSEUDO'];}else{echo "<b class='rose'>LiveAnim</b>";} ?></th>
				<th title="<?php echo $message['DATE_ENVOI']; ?>"><?php echo $message['DATE_ENVOI_simple']; ?></th>
				<th class="<?php if($message['STATUT_MESSAGE'] == "Non lu"){echo "orange";}else if($message['STATUT_MESSAGE'] == "Répondu"){echo "valide";} ?>"><?php echo $message['STATUT_MESSAGE']; ?></th>
				<th><input type="checkbox" name="ids_msg[]" value="<?php echo $message['ID_MESSAGE']; ?>" id="<?php echo $message['ID_MESSAGE']; ?>" /></th>
			</tr>
			<?php
			}
		}else{
			# S'il n'y a pas de message.
			?>
			<tr><th colspan="5"><hr /></th></tr>
			<tr height="60px">
				<th colspan="5"><center class="valide">Vous n'avez aucun message dans votre boîte de réception.</center></th>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
		if($nb_result[0]['nb_message'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_message'], $page_actuelle);
		}
	?>
	<br />
	<?php  
	if(count($messages) > 0){
	?>
	<input height="20px" width="20px" style="padding-right:5px;" class="fright" onclick="return confirm('Souhaitez vous vraiment supprimer les messages sélectionnés ?');" type="image" src="<?php echo $oCL_page->getImage('petite_croix'); ?>" alt="Supprimer les messages sélectionnés" title="Supprimer les messages sélectionnés" />
	<br class="clear" />
	<?php
	}
	?>
	<br />
	<div>
		<center class='petit'>
			Vous avez <?php 
				echo $nb_result[0]['nb_message']; ?> message(s) au total.<br />
		</center>
	</div>
	</form>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>