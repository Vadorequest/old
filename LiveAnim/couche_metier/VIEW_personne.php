<?php
class VIEW_personne{

	private $ID_PERSONNE;
	private $ID_IP;
	private $ID_ANNONCE;
	private $ID_PACK;
	private $ID_CONTRAT;
	private $ID_TYPES;
	private $ID_MESSAGE;
	
	private $sql;
	
	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_IP = "";
		$this->ID_ANNONCE = "";
		$this->ID_PACK = "";
		$this->ID_CONTRAT = "";
		$this->ID_TYPES = "";
		$this->ID_MESSAGE = "";
	}
	
	// ----------------------------------------------------------- Vues sur la table des IP.
	
	public function SELECT_toutes_ip_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT * FROM ip_personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_CONNEXION DESC;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_date_creation_compte_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		
		$this->sql = "SELECT * FROM ip_personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_CONNEXION ASC LIMIT 0,1;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
						);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_dernieres_connexions_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
	
		$this->sql = "SELECT * FROM ip_personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_CONNEXION DESC $limit;";
		$params = array(    
						":ID_PERSONNE"=>$this->ID_PERSONNE,
						);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_personne_by_ID_CONTRAT_et_nonTYPE_PERSONNE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');

		$this->sql = "SELECT personne.ID_PERSONNE, personne.PSEUDO, personne.EMAIL ".
		"FROM contrat_personne LEFT OUTER JOIN personne ON contrat_personne.ID_PERSONNE = personne.ID_PERSONNE ".
		"WHERE contrat_personne.ID_CONTRAT=:ID_CONTRAT AND personne.TYPE_PERSONNE<>:TYPE_PERSONNE;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':TYPE_PERSONNE' =>$this->TYPE_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_personne_by_ID_CONTRAT_et_TYPE_PERSONNE($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');

		$this->sql = "SELECT personne.ID_PERSONNE, personne.PSEUDO, personne.EMAIL ".
		"FROM contrat_personne LEFT OUTER JOIN personne ON contrat_personne.ID_PERSONNE = personne.ID_PERSONNE ".
		"WHERE contrat_personne.ID_CONTRAT=:ID_CONTRAT AND personne.TYPE_PERSONNE=:TYPE_PERSONNE;";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':TYPE_PERSONNE' =>$this->TYPE_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_prestataires_lors_annulation_contrat($oMSG){
		$this->ID_DEPARTEMENT = $oMSG->getData('ID_DEPARTEMENT');

		$this->sql = "SELECT personne.ID_PERSONNE, personne.EMAIL, personne.PSEUDO ".
		"FROM  personne LEFT OUTER JOIN pack_personne ON personne.ID_PERSONNE = pack_personne.ID_PERSONNE ".
		"LEFT OUTER JOIN pack ON pack_personne.ID_PACK = pack.ID_PACK ".
		"WHERE personne.DEPARTEMENTS LIKE '%".$this->ID_DEPARTEMENT."%' AND pack.ALERTE_NON_DISPONIBILITE = 1 AND personne.TYPE_PERSONNE = 'Prestataire' ".
		"AND pack_personne.DATE_DEBUT < NOW() AND pack_personne.DATE_FIN > NOW() AND personne.VISIBLE = 1 GROUP BY personne.ID_PERSONNE;";
		
		$params = array(  
							
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_PERSONNE_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');
		$criteres = $oMSG->getData('criteres');
		$sql_LIKE = $oMSG->getData('sql_LIKE');
		$ROLES = $oMSG->getData('ROLES');

		$this->sql = "SELECT COUNT(DISTINCT personne.ID_PERSONNE) AS nb_personne ".
		"FROM personne LEFT OUTER JOIN pack_personne ON personne.ID_PERSONNE = pack_personne.ID_PERSONNE ".
		"LEFT OUTER JOIN pack ON pack.ID_PACK = pack_personne.ID_PACK ".
		"WHERE personne.VISIBLE=:VISIBLE AND personne.TYPE_PERSONNE=:TYPE_PERSONNE ".
		"AND pack_personne.DATE_DEBUT < NOW() AND pack_personne.DATE_FIN > NOW() $criteres ";
		# On rajoute la clause LIKE
		$this->sql.= " ".$sql_LIKE." ";

		
		$params = array(  
						':VISIBLE'=> $this->VISIBLE,	
						':TYPE_PERSONNE'=> $this->TYPE_PERSONNE,	
						':ROLES'=> $ROLES,	
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_personnes_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');
		$criteres = $oMSG->getData('criteres');
		$ORDER_BY = $oMSG->getData('ORDER_BY');
		$sql_LIKE = $oMSG->getData('sql_LIKE');
		$ROLES = $oMSG->getData('ROLES');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
		

		$this->sql = "SELECT personne.ID_PERSONNE, personne.PSEUDO, personne.URL_PHOTO_PRINCIPALE, personne.STATUT_PERSONNE, personne.ROLES, ".
		"personne.NOM, personne.PRENOM, personne.CIVILITE ".
		"FROM personne LEFT OUTER JOIN pack_personne ON personne.ID_PERSONNE = pack_personne.ID_PERSONNE ".
		"LEFT OUTER JOIN pack ON pack.ID_PACK = pack_personne.ID_PACK ".
		"WHERE personne.VISIBLE=:VISIBLE AND personne.TYPE_PERSONNE=:TYPE_PERSONNE ".
		"AND pack_personne.DATE_DEBUT < NOW() AND pack_personne.DATE_FIN > NOW() $criteres ";
		# On rajoute la clause LIKE
		$this->sql.= " ".$sql_LIKE." GROUP BY personne.ID_PERSONNE ";

		# On rajoute la clause ORDER BY
		if(!empty($ORDER_BY)){
			$this->sql.= " ".$ORDER_BY." ";
		}
		$this->sql.= "LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
						':VISIBLE'=> $this->VISIBLE,	
						':TYPE_PERSONNE'=> $this->TYPE_PERSONNE,	
						':ROLES'=> $ROLES,	
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_date_creation_compte($oMSG){
		$this->TYPE_PERSONNE = $oMSG->getData("TYPE_PERSONNE");
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');

		$this->sql = "SELECT DISTINCT personne.ID_PERSONNE, personne.PSEUDO, personne.URL_PHOTO_PRINCIPALE, ip_personne.ID_IP, ip_personne.DATE_CONNEXION ".
		"FROM personne LEFT OUTER JOIN ip_personne ON personne.ID_PERSONNE = ip_personne.ID_PERSONNE ".
		"WHERE personne.TYPE_PERSONNE=:TYPE_PERSONNE AND VISIBLE=1 ".
		"GROUP BY personne.ID_PERSONNE ORDER BY DATE_CONNEXION DESC LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(    
					":TYPE_PERSONNE"=>$this->TYPE_PERSONNE,
						);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_artistes_premium($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');

		

		$this->sql = "SELECT personne.ID_PERSONNE, personne.PSEUDO, personne.URL_PHOTO_PRINCIPALE, personne.ROLES, pack.NOM, pack.CV_ACCESSIBLE, ".
		"pack.CV_VISIBILITE ".
		"FROM personne LEFT OUTER JOIN pack_personne ON personne.ID_PERSONNE = pack_personne.ID_PERSONNE ".
		"LEFT OUTER JOIN pack ON pack.ID_PACK = pack_personne.ID_PACK ".
		"WHERE personne.VISIBLE=:VISIBLE AND personne.TYPE_PERSONNE=:TYPE_PERSONNE ".
		"AND pack_personne.DATE_DEBUT < NOW() AND pack_personne.DATE_FIN > NOW() GROUP BY personne.ID_PERSONNE ORDER BY pack.CV_VISIBILITE DESC;";
		
		$params = array(  
						':VISIBLE'=> $this->VISIBLE,	
						':TYPE_PERSONNE'=> $this->TYPE_PERSONNE,	
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG){
		$this->TYPE_PERSONNE = $oMSG->getData('TYPE_PERSONNE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		
		$this->sql = "SELECT personne.ID_PERSONNE, personne.PSEUDO, personne.URL_PHOTO_PRINCIPALE, personne.NOM, personne.PRENOM, personne.CIVILITE, personne.EMAIL ".
		"FROM personne ".
		"WHERE personne.VISIBLE=:VISIBLE AND TYPE_PERSONNE=:TYPE_PERSONNE ".
		"ORDER BY PSEUDO, NOM, PRENOM;";
		
		$params = array( 
						':VISIBLE'=> $this->VISIBLE,
						':TYPE_PERSONNE'=> $this->TYPE_PERSONNE,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}