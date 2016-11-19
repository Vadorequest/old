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
require_once('couche_metier/PCS_personne.php');

# On charge la librairie facebook
require_once('ressources/facebook/facebook.php');

# On déclare nos constantes.
define('CLE_PUBLIQUE', '219360858133211');
define('CLE_SECRETE', 'f0e00266bfcfc71a3c648da4900d614a');

# On instancie nos objets.
$oMSG = new MSG();
$oPCS_personne = new PCS_personne();
$oFacebook = new Facebook(array(
		  'appId'  => CLE_PUBLIQUE,
		  'secret' => CLE_SECRETE,
		  'cookie' => true,
));

try{
	# On récupère les identifiants du visiteur
	$user_id = $oFacebook->getUser();
	
	# On récupère l'utilisateur
	$user_facebook = $oFacebook->api('/me');

}catch(Exception $e){
	# Sinon alors c'est que le membre n'est pas connecté à Facebook.
	echo "Une erreur s'est produite avec Facebook. Veuillez vous connecter normalement, merci.<br />";
	$json_user_id = json_encode($user_id);
	$json_user_facebook = json_encode($user_facebook);
	?>
	<script type="text/javascript">
		console.log(<?php echo "'user_id: '+".$json_user_id; ?>);
		console.log(<?php echo "'user_facebook: '+".$json_user_facebook; ?>);
		console.log(<?php echo "'Error: '+'".$e; ?>');
	</script>
	<?php
	//die();
}	
var_dump($user_id);

# On vérifie qu'on ai bien récupéré qqn.
if(isset($user_id) && !empty($user_id)){

	# On récupère le compte utilisateur pour l'uid facebook en cours.
	$oMSG->setData('ID_FACEBOOK', $uid);
	
	$Personne = $oPCS_personne->fx_recuperer_compte_by_ID_FACEBOOK($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# Si un compte possède cet ID alors on charge la session et on connecte le membre.
	if(isset($Personne) && !empty($Personne)){
		
	}else{
		# Sinon on dit au membre qu'il n'a pas lié son compte LiveAnim à un compte Facebook.
		$_SESSION['connexion']['message'] = "<span class='orange'>Vous n'avez pas lié votre compte Facebook à votre compte LiveAnim.<br />".
		"Pour lier votre compte Facebook il vous suffit de vous connecter normalement puis d'aller dans 'Mon profil'-> 'Facebook' et de lier le compte !<br />".
		"Vous pourrez ainsi vous connecter d'un simple clic à l'avenir. <span class='petit'>(À condition d'être connecté à Facebook !)</span></span><br />";
		$_SESSION['connexion']['message_affiche'] = false;
		
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Sinon alors c'est que le membre n'est pas connecté à Facebook.
	$_SESSION['connexion']['message'] = "<span class='orange'>Vous n'êtes pas connecté à Facebook.</span><br />";
	$_SESSION['connexion']['message_affiche'] = false;
	
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}

?>


<?php
/** ### Récupérer des informations de la BDD de FB ###

	$fql = "SELECT uid, name, first_name, middle_name, last_name, sex, locale, pic_small_with_logo, username ".
	"FROM user WHERE uid=".$uid;
	
	$params = Array(
			'method' => 'fql_query',
			'query' => $fql,
			'callback' => '',
				);
				
	$fb = $oFacebook->api($params);
	
	var_dump($fb);
*/
?>