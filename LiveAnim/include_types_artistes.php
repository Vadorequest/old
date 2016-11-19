<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_types.php');

$oMSG = new MSG();
$oPCS_types = new PCS_types();

$oMSG->setData('ID_FAMILLE_TYPES', 'Roles');

$types_actuels = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="section">
	<img style="" alt="Tous les artistes"
 src="images/tousartistes.png">
<br />
<br />
	<ul class="adv-menu">
		<li><a href="<?php echo $oCL_page->getPage('liste_artiste')."#resultats_recherche"; ?>"><img alt="Tous" width="20" height="20" src="<?php echo $oCL_page->getImage('etoile_menu'); ?>" /> Tous</a></li>
		<?php
		foreach($types_actuels as $key=>$type){
		?>
			<li><a href="<?php echo $oCL_page->getPage('liste_artiste')."?role=".$type['ID_TYPES']."#resultats_recherche"; ?>"><img alt="<?php echo $type['ID_TYPES']; ?>" width="20" height="20" src="<?php echo $oCL_page->getImage('etoile_menu'); ?>" /> <?php echo $type['ID_TYPES']; ?></a></li>
		<?php
		}
		?>
	</ul>
</div>