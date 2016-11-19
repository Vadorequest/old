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
	<center> <img style="" alt="FAQ"
 src="images/gestion-faq.png"></center>
<br />
<br />

<div class="padding_LR" id="corps_faq">
	<?php include_once('include_file_include_faq.php'); ?>	
</div>