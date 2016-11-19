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
require_once('couche_metier/PCS_pub.php');

$oMSG = new MSG();
$oPCS_pub = new PCS_pub();

# On récupère les pubs qui doivent être affichés ici.
$POSITION = 2;
$oMSG->setData('POSITION', $POSITION);

$pubs = $oPCS_pub->fx_recuperer_pubs_by_POSITION($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
$nb_pubs = count($pubs);

if($nb_pubs > 0){
	# On en affiche une au hasard.
	$key = mt_rand(0, $nb_pubs-1);

	?>
	<br />
	<center>	
		<?php
		if(!isset($_SESSION['pack']['activé']) || $_SESSION['pack']['PUBS'] != false){
		?>
			<div id="pub_bas" width="468px" height="60px">
				<?php echo $pubs[$key]['CONTENU']; ?>
			</div>
		<?php
		}
		?>
	</center>
<?php
}
?>