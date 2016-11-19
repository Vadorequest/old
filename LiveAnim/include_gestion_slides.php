<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<h2></h2><br />
	<br />
	<?php
	if(isset($_SESSION['gestion_slides']['message']) && $_SESSION['gestion_slides']['message_affiche'] == false){
		echo $_SESSION['gestion_slides']['message']."<br />";
		$_SESSION['gestion_slides']['message_affiche'] = true;
	}
	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Visualisation des slides existants:</legend>
		<br />
		<table width="100%">
			<tr>
				<th>Slide:</th>
				<th>Ordre:</th>
				<th>Accès:</th>
				<th>Visible:</th>
				<th>Voir fiche:</th>
			</tr>
			<?php
			foreach($slides as $key=>$slide){
			?>
				<tr><th colspan="5"><hr /></th></tr>
				<tr>
				<th><img width="250px" height="150px" src="<?php echo $slide['URL']; ?>" alt="<?php echo $slide['TITRE']; ?>" title="<?php echo $slide['TITRE']; ?>" /></th>
				<th><?php echo $slide['ORDRE']; ?></th>
				<th><?php echo str_replace(',', '<br />', $slide['ACCES']); ?></th>
				<th><?php if($slide['VISIBLE']){echo "Oui";}else{echo "Non";} ?></th>
				<th><a href="<?php echo $oCL_page->getPage('modifier_slide')."?id_slide=".$slide['ID_SLIDE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Modifier" title="Modifier" /></a></th>
			</tr>
			<?php
			}
			?>
		</table>
		<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Ajouter un slide:</legend>
		<br />
		<?php 
			$formulaire = "ajout";
			require_once('include_form_ajouter_modifier_slide.php'); 
		?>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>