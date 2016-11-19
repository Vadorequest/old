<?php
if(!isset($_SESSION)){
	session_start();
}
error_reporting(-1);
# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
	<img style="" alt="Prestations faites"
 src="images/prestations-faites.png">
<br />
<br />
	
	<?php
	if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
	}
	?>
	
	<fieldset class="padding_LR"><legend class="legend_basique">Liste&nbsp;de&nbsp;vos&nbsp;prestations&nbsp;effectuées:&nbsp;</legend>
		<?php
		if($nb_result[0]['nb_contrat'] == 0){
		?>
			<br />
			<center class="orange">
				<?php
				if(isset($_GET['rq']) && $_GET['rq'] == "futures"){
				?>
					Vous n'avez pas de prestation prévue en ce moment.
				<?php
				}else{
				?>
					Vous n'avez jamais effectué de prestation.<br />
					<span class="petit noir">(Les prestations correspondent à des contrats validés et effectués.)<br /></span>
				<?php
				}
				?>
			</center>
		<?php
		}
		foreach($prestations as $key=>$prestation){
			# On récupère la personne avec qui le contrat a été effectué.
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_personne.php');
			
			$oMSG = new MSG();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('ID_CONTRAT', $prestation['ID_CONTRAT']);
			
			$id_organisateur = $oPCS_contrat->fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			$oMSG->setData('ID_PERSONNE', $id_organisateur[0]['ID_PERSONNE']);
			
			$organisateur = $oPCS_personne->fx_recuperer_compte_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		?>
			<div class="padding_LR">
				<br />
				<center><b>Prestation concernant l'annonce <a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$prestation['ID_ANNONCE']; ?>"><?php echo $prestation['TITRE']; ?></a>:</b></center><br />
				<br />
				<?php
				if(isset($_GET['rq']) && $_GET['rq'] == "futures"){
				# On arrange juste la phrase pour que le temps soit logique.
				?>
					La prestation aura lieue au <span class="rose"><?php echo $prestation['ADRESSE'].", ".$prestation['CP']." ".$prestation['VILLE']; ?></span> le <?php echo $prestation['DATE_FIN_simple']; ?>.<br />
					Le contrat a été crée le <span class="rose"><?php echo $prestation['DATE_CONTRAT']; ?></span> puis validé par <a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$organisateur['ID_PERSONNE']; ?>"><?php echo $organisateur[0]['PSEUDO']; ?></a>. <br />
					La somme convenue est de <span class="rose"><?php echo $prestation['PRIX'] ?>€</span>.<br />
				<?php
				}else{
				?>
					La prestation à eue lieue au <span class="rose"><?php echo $prestation['ADRESSE'].", ".$prestation['CP']." ".$prestation['VILLE']; ?></span> le <?php echo $prestation['DATE_FIN_simple']; ?>.<br />
					Le contrat a été crée le <span class="rose"><?php echo $prestation['DATE_CONTRAT']; ?></span> puis validé par <a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$organisateur['ID_PERSONNE']; ?>"><?php echo $organisateur[0]['PSEUDO']; ?></a>. <br />
					La somme convenue était de <span class="rose"><?php echo $prestation['PRIX'] ?>€</span>.<br />
				<?php
				}
				?>
				<br />
				<a href="<?php echo $oCL_page->getPage('contrat')."?id_contrat=".$prestation['ID_CONTRAT']; ?>">Voir le contrat.</a><br />
				<?php
				if($_SESSION['pack']['CONTRATS_PDF']){
				?>
					<a href="<?php echo $oCL_page->getPage('contrat_pdf')."?id_contrat=".$prestation['ID_CONTRAT']; ?>">Voir/Télécharger le contrat au format PDF.</a><br />
				<?php
				}else{
				?>
					<span class="orange petit">Vous ne pouvez pas bénéficier du contrat sous format pdf.</span><br />
				<?php
				}
				?>
			</div>
			<br />
			<hr />
			
		<?php
		}
		?>
	</fieldset>
	
	
	<?php
	if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
	}
	?>
	
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>