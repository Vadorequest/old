<?php
class MPG_personne{

	private $ID_PERSONNE;
	private $PSEUDO;
	private $NOM;
	private $PRENOM;
	private $DESCRIPTION;
	private $URL_PHOTO_PRINCIPALE;
	private $DATE_NAISSANCE;
	private $CIVILITE;
	private $EMAIL;
	private $MDP;
	private $TYPE_PERSONNE;
	private $STATUT_PERSONNE;
	private $CONNAISSANCE_SITE;
	private $NEWSLETTER;
	private $OFFRES_ANNONCEURS;
	private $DEPARTEMENTS;
	private $VILLE;
	private $ADRESSE;
	private $CP;
	private $TEL_FIXE;
	private $TEL_PORTABLE;
	private $REDUCTION;
	private $PARRAIN;
	private $SIRRET;
	private $TARIFS;
	private $DISTANCE_PRESTATION_MAX;
	private $CV_VIDEO;
	private $MATERIEL;
	private $VISIBLE;
	private $DATE_BANNISSEMENT;
	private $PERSONNE_SUPPRIMEE;
	private $DATE_SUPPRESSION_REELLE;
	private $RAISON_SUPPRESSION;	
	private $CLE_ACTIVATION;
	private $ANNONCES_VISITEES;
	private $ROLES;
	private $ID_FACEBOOK;
	private $DERNIERE_ACTIVITE;
	
	# IP
	private $ID_IP;
	private $IP_COOKIE;
	private $COOKIE_DETRUIT;
	private $DATE_CONNEXION;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_PERSONNE = "";
	$this->PSEUDO = "";
	$this->NOM = "";
	$this->PRENOM = "";
	$this->DESCRIPTION = "";
	$this->URL_PHOTO_PRINCIPALE = "";
	$this->DATE_NAISSANCE = "";
	$this->CIVILITE = "";
	$this->EMAIL = "";
	$this->MDP = "";
	$this->TYPE_PERSONNE = "";
	$this->STATUT_PERSONNE = "";
	$this->CONNAISSANCE_SITE = "";
	$this->NEWSLETTER = "";
	$this->OFFRES_ANNONCEURS = "";
	$this->DEPARTEMENTS = "";
	$this->VILLE = "";
	$this->ADRESSE = "";
	$this->CP = "";
	$this->TEL_FIXE = "";
	$this->TEL_PORTABLE = "";
	$this->REDUCTION = "";
	$this->PARRAIN = "";
	$this->SIRRET = "";
	$this->TARIFS = "";
	$this->DISTANCE_PRESTATION_MAX = "";
	$this->CV_VIDEO = "";
	$this->MATERIEL = "";
	$this->VISIBLE = "";
	$this->DATE_BANNISSEMENT = "";
	$this->PERSONNE_SUPPRIMEE = "";
	$this->DATE_SUPPRESSION_REELLE = "";
	$this->RAISON_SUPPRESSION = "";	
	$this->CLE_ACTIVATION = "";	
	$this->ANNONCES_VISITEES = "";	
	$this->ROLES = "";	
	$this->ID_FACEBOOK = "";	
	$this->DERNIERE_ACTIVITE = "";	
	
	# IP
	$this->ID_IP = "";
	$this->IP_COOKIE = "";
	$this->COOKIE_DETRUIT = "";	
	$this->DATE_CONNEXION = "";
	}
	
	public function SELECT_COUNT_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
	
		$this->sql = "SELECT COUNT(PSEUDO) AS nb_pseudo FROM personne WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_pseudo_by_PSEUDO_et_MDP($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(PSEUDO) AS nb_pseudo FROM personne WHERE PSEUDO=:PSEUDO AND MDP=:MDP;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_personne_by_ID_PERSONNE_et_MDP($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne FROM personne WHERE ID_PERSONNE=:ID_PERSONNE AND MDP=:MDP;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_EMAIL($oMSG){
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT COUNT(EMAIL) AS nb_email FROM personne WHERE EMAIL=:EMAIL;";
		$params = array(    
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_compte_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT * FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_compte_min_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, TYPE_PERSONNE, EMAIL ".
		"FROM personne ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
	
		$this->sql = "SELECT * FROM personne WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_PSEUDO_et_EMAIL($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT * FROM personne WHERE PSEUDO=:PSEUDO AND EMAIL=:EMAIL;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_by_EMAIL_et_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne, CLE_ACTIVATION  FROM personne WHERE CLE_ACTIVATION=:CLE_ACTIVATION AND EMAIL=:EMAIL AND VISIBLE=false;";
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_IP_by_ID_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "SELECT COUNT(ID_IP) AS nb_IP FROM ip WHERE ID_IP=:ID_IP;";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_IP_by_IP_COOKIE($oMSG){
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
	
		$this->sql = "SELECT COUNT(ID_IP) AS nb_IP FROM ip WHERE ID_IP=:IP_COOKIE;";
		$params = array(    
					":IP_COOKIE"=>$this->IP_COOKIE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE CLE_ACTIVATION<>:CLE_ACTIVATION GROUP BY ID_PERSONNE HAVING COUNT(ID_IP) >= 1 ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";# ATTENTION: "!=" et non "="
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_ID_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE ID_IP=:ID_IP GROUP BY PSEUDO ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_IP_COOKIE($oMSG){
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE IP_COOKIE=:IP_COOKIE GROUP BY PSEUDO ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";
		$params = array(    
					":IP_COOKIE"=>$this->IP_COOKIE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_tous_membres($oMSG){
		$champs = $oMSG->getData("champs");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL FROM personne ORDER BY PSEUDO;";
		$params = array(    
					
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_tous_membres($oMSG){
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne FROM personne;";
		$params = array(    
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_tous_membres_by_LIMIT($oMSG){
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, URL_PHOTO_PRINCIPALE, EMAIL, TYPE_PERSONNE, VISIBLE, DATE_BANNISSEMENT, PERSONNE_SUPPRIMEE, ".
		"DATE_SUPPRESSION_REELLE, RAISON_SUPPRESSION, CLE_ACTIVATION FROM personne ORDER BY PSEUDO, TYPE_PERSONNE, EMAIL LIMIT $debut_affichage, ".
		"$nb_result_affiches ;";
		$params = array(    
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function SELECT_COUNT_by_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_comptes ".
		"FROM ip_personne NATURAL JOIN personne WHERE CLE_ACTIVATION!=:CLE_ACTIVATION AND (SELECT COUNT(ID_IP) FROM ip_personne) > 1;";# ATTENTION: "!=" et non "="
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG){
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_comptes_supprimes ".
		"FROM personne WHERE PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE AND VISIBLE=:VISIBLE;";
		$params = array(    
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_comptes_supprimes_par_utilisateur($oMSG){
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, EMAIL, TEL_FIXE, TEL_PORTABLE, RAISON_SUPPRESSION ".
		"FROM personne WHERE PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE AND VISIBLE=:VISIBLE ORDER BY DATE_SUPPRESSION_REELLE DESC;";
		$params = array(    
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT REDUCTION FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_PERSONNE_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_personne FROM personne WHERE ID_PERSONNE=:ID_PERSONNE AND EMAIL=:EMAIL AND MDP=:MDP;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":EMAIL"=>$this->EMAIL,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_filleuls_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_personne FROM personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY PSEUDO;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_filleuls_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, TYPE_PERSONNE FROM personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY PSEUDO;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_adresse_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, TYPE_PERSONNE, ADRESSE, CP, VILLE FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ANNONCES_VISITEES FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_PSEUDO_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT PSEUDO FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_ID_FACEBOOK($oMSG){
		$this->ID_FACEBOOK = $oMSG->getData("ID_FACEBOOK");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, EMAIL, TYPE_PERSONNE, PARRAIN, REDUCTION, ANNONCES_VISITEES ".
		"FROM personne WHERE ID_FACEBOOK=:ID_FACEBOOK;";
		$params = array(    
					":ID_FACEBOOK"=>$this->ID_FACEBOOK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_tous_PARRAIN($oMSG){
	
		$this->sql = "SELECT COUNT(PARRAIN) as nb_parrain, PARRAIN FROM personne WHERE PARRAIN != 'Aucun' GROUP BY PARRAIN ORDER BY nb_parrain DESC;";
		
		$params = array(    
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_connectes($oMSG){
		$this->DERNIERE_ACTIVITE = $oMSG->getData("DERNIERE_ACTIVITE");
		
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_personne FROM personne WHERE DERNIERE_ACTIVITE>:DERNIERE_ACTIVITE;";
		
		$params = array(    
				":DERNIERE_ACTIVITE"=>$this->DERNIERE_ACTIVITE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------------------------------- INSERT
	
	public function INSERT_all($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->MDP = $oMSG->getData("MDP");
		$this->TYPE_PERSONNE = $oMSG->getData("TYPE_PERSONNE");
		$this->CONNAISSANCE_SITE = $oMSG->getData("CONNAISSANCE_SITE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");
		$this->PARRAIN = $oMSG->getData("PARRAIN");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		
	
		$this->sql = "INSERT INTO personne (PSEUDO, NOM, PRENOM, CIVILITE, EMAIL, MDP, TYPE_PERSONNE, CONNAISSANCE_SITE, NEWSLETTER, OFFRES_ANNONCEURS, PARRAIN, VISIBLE, CLE_ACTIVATION) ".
					 "VALUES (:PSEUDO, :NOM, :PRENOM, :CIVILITE, :EMAIL, :MDP, :TYPE_PERSONNE, :CONNAISSANCE_SITE, :NEWSLETTER, :OFFRES_ANNONCEURS, :PARRAIN, :VISIBLE, :CLE_ACTIVATION);";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":EMAIL"=>$this->EMAIL,
					":MDP"=>$this->MDP,
					":TYPE_PERSONNE"=>$this->TYPE_PERSONNE,
					":CONNAISSANCE_SITE"=>$this->CONNAISSANCE_SITE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					":PARRAIN"=>$this->PARRAIN,
					":VISIBLE"=>$this->VISIBLE,
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function INSERT_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "INSERT INTO ip (ID_IP) VALUES (:ID_IP);";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function INSERT_liaison_IP_et_PERSONNE($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
		$this->COOKIE_DETRUIT = $oMSG->getData("COOKIE_DETRUIT");
		$this->DATE_CONNEXION = $oMSG->getData("DATE_CONNEXION");
	
		$this->sql = "INSERT INTO ip_personne (ID_IP, ID_PERSONNE, IP_COOKIE, COOKIE_DETRUIT, DATE_CONNEXION) VALUES (:ID_IP, :ID_PERSONNE, :IP_COOKIE, :COOKIE_DETRUIT, :DATE_CONNEXION);";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":IP_COOKIE"=>$this->IP_COOKIE,
					":COOKIE_DETRUIT"=>$this->COOKIE_DETRUIT,
					":DATE_CONNEXION"=>$this->DATE_CONNEXION,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------------------- UPDATE
	
	public function UPDATE_activation_compte($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "UPDATE personne SET CLE_ACTIVATION=:CLE_ACTIVATION, VISIBLE=true WHERE EMAIL=:EMAIL;";
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_TYPE_PERSONNE_by_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->TYPE_PERSONNE = $oMSG->getData("TYPE_PERSONNE");
	
		$this->sql = "UPDATE personne SET TYPE_PERSONNE=:TYPE_PERSONNE WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":TYPE_PERSONNE"=>$this->TYPE_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_validite_compte_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->DATE_BANNISSEMENT = $oMSG->getData("DATE_BANNISSEMENT");
		$this->DATE_SUPPRESSION_REELLE = $oMSG->getData("DATE_SUPPRESSION_REELLE");
		$this->RAISON_SUPPRESSION = $oMSG->getData("RAISON_SUPPRESSION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
	
		$this->sql = "UPDATE personne SET DATE_BANNISSEMENT=:DATE_BANNISSEMENT, DATE_SUPPRESSION_REELLE=:DATE_SUPPRESSION_REELLE, RAISON_SUPPRESSION=:RAISON_SUPPRESSION, VISIBLE=:VISIBLE, PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":DATE_BANNISSEMENT"=>$this->DATE_BANNISSEMENT,
					":DATE_SUPPRESSION_REELLE"=>$this->DATE_SUPPRESSION_REELLE,
					":RAISON_SUPPRESSION"=>$this->RAISON_SUPPRESSION,
					":VISIBLE"=>$this->VISIBLE,
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_fiche_personnelle_basique_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, ".
		"URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, EMAIL=:EMAIL, TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, REDUCTION=:REDUCTION, ".
		"ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE, NEWSLETTER=:NEWSLETTER, OFFRES_ANNONCEURS=:OFFRES_ANNONCEURS ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":EMAIL"=>$this->EMAIL,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":REDUCTION"=>$this->REDUCTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function UPDATE_fiche_personnelle_complete_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");
		
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->STATUT_PERSONNE = $oMSG->getData("STATUT_PERSONNE");
		$this->DEPARTEMENTS = $oMSG->getData("DEPARTEMENTS");
		$this->SIRET = $oMSG->getData("SIRET");
		$this->TARIFS = $oMSG->getData("TARIFS");
		$this->DISTANCE_PRESTATION_MAX = $oMSG->getData("DISTANCE_PRESTATION_MAX");
		$this->CV_VIDEO = $oMSG->getData("CV_VIDEO");
		$this->MATERIEL = $oMSG->getData("MATERIEL");
		$this->ROLES = $oMSG->getData("ROLES");
		// Les ANNONCES_VISITEES ne sont pas gérées pour le moment. 

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, ".
		"URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, EMAIL=:EMAIL, TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, REDUCTION=:REDUCTION, ADRESSE=:ADRESSE, ".
		"CP=:CP, VILLE=:VILLE, NEWSLETTER=:NEWSLETTER, OFFRES_ANNONCEURS=:OFFRES_ANNONCEURS, DESCRIPTION=:DESCRIPTION, ".
		"STATUT_PERSONNE=:STATUT_PERSONNE, DEPARTEMENTS=:DEPARTEMENTS, SIRET=:SIRET, TARIFS=:TARIFS, ".
		"DISTANCE_PRESTATION_MAX=:DISTANCE_PRESTATION_MAX, CV_VIDEO=:CV_VIDEO, MATERIEL=:MATERIEL, ROLES=:ROLES ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":EMAIL"=>$this->EMAIL,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":REDUCTION"=>$this->REDUCTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":STATUT_PERSONNE"=>$this->STATUT_PERSONNE,
					":DEPARTEMENTS"=>$this->DEPARTEMENTS,
					":SIRET"=>$this->SIRET,
					":TARIFS"=>$this->TARIFS,
					":DISTANCE_PRESTATION_MAX"=>$this->DISTANCE_PRESTATION_MAX,
					":CV_VIDEO"=>$this->CV_VIDEO,
					":MATERIEL"=>$this->MATERIEL,
					":ROLES"=>$this->ROLES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_MDP_by_ID_PERSONNE($oMSG){
		$this->MDP = $oMSG->getData("MDP");
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "UPDATE personne SET MDP=:MDP WHERE ID_PERSONNE=:ID_PERSONNE";
		$params = array(    
					":MDP"=>$this->MDP,
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_infos_perso_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, ".
		"TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");

	
		$this->sql = "UPDATE personne SET REDUCTION=:REDUCTION WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":REDUCTION"=>$this->REDUCTION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_REDUCTION_by_ID_PARRAIN($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");

	
		$this->sql = "UPDATE personne SET REDUCTION=REDUCTION+:REDUCTION WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":REDUCTION"=>$this->REDUCTION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ANNONCES_VISITEES = $oMSG->getData("ANNONCES_VISITEES");

	
		$this->sql = "UPDATE personne SET ANNONCES_VISITEES=:ANNONCES_VISITEES WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":ANNONCES_VISITEES"=>$this->ANNONCES_VISITEES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_DERNIERE_ACTIVITE_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->DERNIERE_ACTIVITE = $oMSG->getData("DERNIERE_ACTIVITE");

	
		$this->sql = "UPDATE personne SET DERNIERE_ACTIVITE=:DERNIERE_ACTIVITE WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":DERNIERE_ACTIVITE"=>$this->DERNIERE_ACTIVITE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}
?>