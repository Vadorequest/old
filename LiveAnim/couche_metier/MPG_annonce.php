<?php
class MPG_annonce{

	private $ID_ANNONCE;
	private $ID_PERSONNE;
	private $ID_DEPARTEMENT;
	private $TITRE;
	private $TYPE_ANNONCE;
	private $DATE_ANNONCE;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $ARTISTES_RECHERCHES;
	private $BUDGET;
	private $NB_CONVIVES;
	private $DESCRIPTION;
	private $ADRESSE;
	private $CP;
	private $VILLE;
	private $GOLDLIVE;
	private $VISIBLE;
	private $STATUT;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_ANNONCE = "";
	$this->ID_PERSONNE = "";
	$this->ID_DEPARTEMENT = "";
	$this->TITRE = "";
	$this->TYPE_ANNONCE = "";
	$this->DATE_ANNONCE = "";
	$this->DATE_DEBUT = "";
	$this->DATE_FIN = "";
	$this->ARTISTES_RECHERCHES = "";
	$this->BUDGET = "";
	$this->NB_CONVIVES = "";
	$this->DESCRIPTION = "";
	$this->ADRESSE = "";
	$this->CP = "";
	$this->VILLE = "";
	$this->GOLDLIVE = "";
	$this->VISIBLE = "";
	$this->STATUT = "";
	}
	
	
	public function SELECT_COUNT_ID_ANNONCE_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE VISIBLE=:VISIBLE;";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");
	
		$this->sql = "SELECT ID_ANNONCE, ID_PERSONNE, ID_DEPARTEMENT, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ARTISTES_RECHERCHES, ".
		"BUDGET, NB_CONVIVES, DESCRIPTION, ADRESSE, CP, VILLE, GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_ANNONCE=:ID_ANNONCE;";
		$params = array(    
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_ANNONCE_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
	
		$this->sql = "SELECT ID_ANNONCE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ".
		"GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE ".
		"ORDER BY DATE_ANNONCE DESC LIMIT $debut_affichage, $nb_result_affiches;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_ANNONCE_futures_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_DEBUT > NOW();";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		
		$this->sql = "SELECT ID_ANNONCE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ".
		"GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_DEBUT > NOW() ".
		"ORDER BY DATE_ANNONCE DESC LIMIT $debut_affichage, $nb_result_affiches;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_annonces_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
		$criteres = $oMSG->getData("criteres");
		
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE VISIBLE=:VISIBLE AND STATUT=:STATUT $criteres";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonce_valide_by_ID_ANNONCE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");		

		$this->sql = "SELECT ID_ANNONCE, ID_PERSONNE, TITRE, TYPE_ANNONCE, DATE_DEBUT, DATE_FIN, BUDGET FROM annonce WHERE VISIBLE=:VISIBLE AND STATUT=:STATUT AND ID_ANNONCE=:ID_ANNONCE AND DATE_DEBUT > NOW();";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_toutes_annonces($oMSG){
		
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce;";
		$params = array(    
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_tous_goldlive($oMSG){
		
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE GOLDLIVE=1;";
		$params = array(    
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	// ----------------------------------------------------- INSERT ---------------------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ID_DEPARTEMENT = $oMSG->getData("ID_DEPARTEMENT");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->TYPE_ANNONCE = $oMSG->getData("TYPE_ANNONCE");
		$this->DATE_ANNONCE = $oMSG->getData("DATE_ANNONCE");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->ARTISTES_RECHERCHES = $oMSG->getData("ARTISTES_RECHERCHES");
		$this->BUDGET = $oMSG->getData("BUDGET");
		$this->NB_CONVIVES = $oMSG->getData("NB_CONVIVES");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->GOLDLIVE = $oMSG->getData("GOLDLIVE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
	
		$this->sql = "INSERT INTO annonce (ID_PERSONNE, ID_DEPARTEMENT, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ARTISTES_RECHERCHES, BUDGET, ".
		"NB_CONVIVES, DESCRIPTION, ADRESSE, CP, VILLE, GOLDLIVE, VISIBLE, STATUT) VALUES(:ID_PERSONNE, :ID_DEPARTEMENT, :TITRE, :TYPE_ANNONCE, :DATE_ANNONCE, :DATE_DEBUT, ".
		":DATE_FIN, :ARTISTES_RECHERCHES, :BUDGET, :NB_CONVIVES, :DESCRIPTION, :ADRESSE, :CP, :VILLE, :GOLDLIVE, :VISIBLE, :STATUT);";

		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":ID_DEPARTEMENT"=>$this->ID_DEPARTEMENT,
					":TITRE"=>$this->TITRE,
					":TYPE_ANNONCE"=>$this->TYPE_ANNONCE,
					":DATE_ANNONCE"=>$this->DATE_ANNONCE,
					":DATE_DEBUT"=>$this->DATE_DEBUT,
					":DATE_FIN"=>$this->DATE_FIN,
					":ARTISTES_RECHERCHES"=>$this->ARTISTES_RECHERCHES,
					":BUDGET"=>$this->BUDGET,
					":NB_CONVIVES"=>$this->NB_CONVIVES,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":GOLDLIVE"=>$this->GOLDLIVE,
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------- UPDATE ---------------------------------
	
	public function UPDATE_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");
		$this->ID_DEPARTEMENT = $oMSG->getData("ID_DEPARTEMENT");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->TYPE_ANNONCE = $oMSG->getData("TYPE_ANNONCE");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->ARTISTES_RECHERCHES = $oMSG->getData("ARTISTES_RECHERCHES");
		$this->BUDGET = $oMSG->getData("BUDGET");
		$this->NB_CONVIVES = $oMSG->getData("NB_CONVIVES");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->GOLDLIVE = $oMSG->getData("GOLDLIVE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
	
		$this->sql = "UPDATE annonce SET ID_DEPARTEMENT=:ID_DEPARTEMENT, TITRE=:TITRE, TYPE_ANNONCE=:TYPE_ANNONCE, DATE_DEBUT=:DATE_DEBUT, DATE_FIN=:DATE_FIN, ".
		"ARTISTES_RECHERCHES=:ARTISTES_RECHERCHES, BUDGET=:BUDGET, NB_CONVIVES=:NB_CONVIVES, DESCRIPTION=:DESCRIPTION, ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE, ".
		"GOLDLIVE=:GOLDLIVE, VISIBLE=:VISIBLE, STATUT=:STATUT WHERE ID_ANNONCE=:ID_ANNONCE;";

		$params = array(    
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					":ID_DEPARTEMENT"=>$this->ID_DEPARTEMENT,
					":TITRE"=>$this->TITRE,
					":TYPE_ANNONCE"=>$this->TYPE_ANNONCE,
					":DATE_DEBUT"=>$this->DATE_DEBUT,
					":DATE_FIN"=>$this->DATE_FIN,
					":ARTISTES_RECHERCHES"=>$this->ARTISTES_RECHERCHES,
					":BUDGET"=>$this->BUDGET,
					":NB_CONVIVES"=>$this->NB_CONVIVES,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":GOLDLIVE"=>$this->GOLDLIVE,
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_goldlive_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");
		$this->GOLDLIVE = $oMSG->getData("GOLDLIVE");

	
		$this->sql = "UPDATE annonce SET GOLDLIVE=:GOLDLIVE WHERE ID_ANNONCE=:ID_ANNONCE;";

		$params = array(    
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					":GOLDLIVE"=>$this->GOLDLIVE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}