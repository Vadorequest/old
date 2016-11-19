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
	<h2>Gestion des rôles:</h2><br />
	<br />
	<div id="liste_role_admin_reponse"></div>
	<br />
	<table width="550px">
		<tr class="formulaire">
			<th width="30%">Singulier</th>
			<th width="30%">Pluriel</th>
			<th width="20%">Modifier</th>
			<th width="20%">Supprimer</th>
		</tr>
		<?php
		foreach($roles as $key=>$role){
		?>
		<tr class="tr_<?php echo $role['singulier']; ?>_<?php echo $role['pluriel']; ?>"><th colspan="4"><hr /></th></tr>
		<tr height="30px" class="tr_<?php echo $role['singulier']; ?>_<?php echo $role['pluriel']; ?>">
			<th width="30%" id="th_<?php echo $role['singulier']; ?>">
				<input id="role_singulier_<?php echo $role['singulier']; ?>" name="role_singulier_<?php echo $role['singulier']; ?>[]" type="text" value="<?php echo $role['singulier']; ?>" />
			</th>
			<th width="30%" id="th_<?php echo $role['pluriel']; ?>">
				<input id="role_pluriel_<?php echo $role['pluriel']; ?>" name="role_pluriel_<?php echo $role['pluriel']; ?>[]" type="text" value="<?php echo $role['pluriel']; ?>" />
			</th>
			<th width="20%" id="th_upd_<?php echo $role['singulier']; ?>_<?php echo $role['pluriel']; ?>">
				<input type="button" id="btn_upd_role_<?php echo $role['singulier']; ?>" name="role_<?php echo $role['singulier']; ?>" value="Modifier" onclick="javascript: fx_upd_role('<?php echo $role['singulier']; ?>', '<?php echo $role['pluriel']; ?>');" />
			</th>
			<th width="20%" id="th_del<?php echo $role['singulier']; ?>_<?php echo $role['pluriel']; ?>">
				<input type="button" id="btn_upd_role_<?php echo $role['singulier']; ?>" name="role_<?php echo $role['singulier']; ?>" value="Supprimer" onclick="javascript: fx_del_role('<?php echo $role['singulier']; ?>', '<?php echo $role['pluriel']; ?>');" />
			</th>
		</tr>
		<?php
		}
		?>
	</table>
	<table width="550px" id="table_maj_event"></table>
	<table width="550px">
		<tr><th colspan="4"><hr /></th></tr>
		<tr height="30px">
			<th width="30%">
				<input id="role_singulier_new" name="role_singulier_new" type="text" />
			</th>
			<th width="30%">
				<input id="role_pluriel_new" name="role_pluriel_new" type="text" />
			</th>
			<th width="20%">
				<input type="button" id="btn_add_role_new" name="btn_add_role_new" value="Ajouter" onclick="javascript: fx_add_role(this);" />
			</th>
			<th width="20%">
				&nbsp;
			</th>
		</tr>
	</table>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>