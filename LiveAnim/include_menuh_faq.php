<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
?>
<h2>Sommaire:</h2><br />
<br />
<div id="sommaire_faq" class="padding_LR">
	<?php include_once('include_file_menuh_faq.php'); ?>
 </div>