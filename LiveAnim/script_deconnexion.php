<?php
session_start();

$page_actuelle = $_SESSION['page_actuelle'];
require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

$_SESSION = array();
session_destroy();
session_unset();

header('Location: '.$oCL_page->getPage($page_actuelle));
?>
