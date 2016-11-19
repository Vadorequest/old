<?php
# On charge le nombre de messages non lus. 
if($_SESSION['compte']['connecté'] == true){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_message.php');

	$oMSG = new MSG();
	$oPCS_message = new PCS_message();

	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('STATUT_MESSAGE', 'Non lu');
	$oMSG->setData('VISIBLE', 1);
	
	$nb_messages_non_lus = $oPCS_message->fx_compter_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
	$nb_messages_non_lus = $nb_messages_non_lus[0]['nb_message'];
}
?>
<ul class="nav">

	<li><a href="<?php echo $oCL_page->getPage('accueil'); ?>" <?php if($_SESSION['page_actuelle'] == "accueil"){echo "class='current'";} ?>>Accueil</a></li>
	<?php
	if($_SESSION['compte']['connecté'] == false){
	?>
		<li><a href="<?php echo $oCL_page->getPage('inscription')."#inscriptionh2"; ?>" <?php if($_SESSION['page_actuelle'] == "inscription"){echo "class='current'";} ?>>Inscription</a></li>
	<?php
	}
	?>
	<li><a href="<?php echo $oCL_page->getPage('liste_annonce'); ?>" <?php if($_SESSION['page_actuelle'] == "liste_annonce"){echo "class='current'";} ?>>Annonces</a></li>

	<li><a href="<?php echo $oCL_page->getPage('faq'); ?>" <?php if($_SESSION['page_actuelle'] == "faq"){echo "class='current'";} ?>>Faq</a></li>

	<li><a href="<?php echo $oCL_page->getPage('contact'); ?>" <?php if($_SESSION['page_actuelle'] == "contact"){echo "class='current'";} ?>>Contact</a></li>
	<?php
	if($_SESSION['compte']['connecté'] == true){
	?>
		<li><a href="<?php echo $oCL_page->getPage('messagerie'); ?>" <?php if($_SESSION['page_actuelle'] == "ma_messagerie"){echo "current";} ?>><span title="<?php if($nb_messages_non_lus > 0){echo "Nouveau(x) message(s) !";}else{echo "Consulter votre messagerie";} ?>">Messagerie <?php if($nb_messages_non_lus > 0){echo "<span class='orange petit'>[".$nb_messages_non_lus."]</span>";}?></span></a></li>
	<?php
	}
	?>
</ul>
